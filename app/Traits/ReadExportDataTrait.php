<?php
namespace App\Traits;

use Exception;

use App\Models\Tbl_Log;
use App\Models\File as FileModels;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ReadExportDataTrait {

    public function readFlatFile($db, $id_importation, $type){

        //Route of flat file
        $folderPath = storage_path("app/imports/$db/planos");
        $txtFiles   = glob("$folderPath/*.txt");
        if(count($txtFiles) == 0){
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::ReadExportDataTrait[readFlatFile()] => No se encontraron archivos planos en '.$db
            ]);
            return 1;
        };

        //Folder of Models
        $baseNamespace   = 'App\\Models\\'.ucfirst($db).'\\';
        //All models
        $modelFiles      = File::files(app_path('Models/'.ucfirst($db)));
        $availableModels = [];
        foreach ($modelFiles as $modelFile) {
            //Route of model
            $routeName = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
            if (class_exists($routeName)) {
                //extracts the model name and stores it in the $availableModels array
                $availableModels[$routeName] = (new $routeName())->getTable();
            }
        }

        $fileModels = FileModels::join('custom_migrations', 'custom_migrations.id', '=', 'custom_migrations_id')
                ->where('state', 1)
                ->get(['name_table', 'files.name as nameFile']); 
        
        foreach($fileModels as $file){
            foreach ($availableModels as $modelClass => $tableName) {
                $content = file_get_contents($folderPath.'/'.$file->nameFile);
                if ($file->name_table === $tableName) {
                    $this->info("◘ El archivo plano $file->nameFile coincide con el modelo: $tableName");
                    $this->processFileContent($modelClass, $content, $tableName, $id_importation, $type);
                }
            }
        }
    }

    private function processFileContent($modelClass, $content, $tableName, $id_importation, $type) {
        try {
            $modelInstance = new $modelClass();
            $columnsModelo = $modelInstance->getFillable();
            $autoIncrement = 1;
    
            if ($content === false) {
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Traits::ReadExportDataTrait[processFileContent()] => No se pudo leer el archivo plano '.$modelInstance
                ]);
                return 1;
            }
    
            // Split the content into lines
            $lines = explode("\n", $content);
            $dataToInsert = [];
           
            foreach ($lines as $line) {
                // Verificar si la línea no está vacía antes de procesarla
                if (!empty($line)) {
                    // Split each line into columns using the |
                    $columns = explode("|", $line);

                    // Esta condicion sirve para que tenga en cuenta el autoincrementable
                    if ($tableName == 't05_bex_clientes' || $tableName == 't38_bex_entregas') {
                        $columnsCount = count($columns) + 1;
                    }else{
                        $columnsCount = count($columns);
                    }
             
                    if($columnsCount > count($columnsModelo)){
                        Tbl_Log::create([
                            'id_table'    => $id_importation,
                            'type'        => $type,
                            'descripcion' => 'Traits::ReadExportDataTrait[processFileContent()] => El numero de columnas es mayor al esperado.
                                                => Tabla: '.$tableName.' 
                                                => Columnas archivo plano: '.$columnsCount.', Columnas esperadas: '.count($columnsModelo).'
                                                => Info linea archivo plano que ocasiona conflicto: '.json_encode($columns)
                        ]);
                        return 1;
                    }
                    
                    // Construct an associative array of data for insertion
                    $rowData = [];
                    // Fill rowData with values from $columns, up to the number of fillable columns
                    for ($i = 0; $i < count($columns); $i++) {
                        if ($tableName == 't05_bex_clientes' || $tableName == 't38_bex_entregas') {
                            $j = $i + 1;
                        } else {
                            $j = $i;
                        }
                        $rowData[$columnsModelo[$j]] = isset($columns[$i]) ? trim($columns[$i]) : null;
                    }
                    
                    // Insert the data into the array to be bulk-inserted
                    if ($tableName == 't05_bex_clientes' && !empty($rowData)) {
                        //Inserta un autoincrement
                        $rowData['consecutivo'] = $autoIncrement++;
                    }
                    $dataToInsert[] = array_map('utf8_encode', $rowData);
                }
            }
            
            // Bulk insert the data into the corresponding model
            if ($modelInstance && !empty($dataToInsert)) {
                $chunks = array_chunk($dataToInsert, 1000); // Divide en lotes de 1000 registros
                foreach ($chunks as $chunk) {
                    DB::transaction(function () use ($modelInstance, $chunk, $tableName) {
                        $modelInstance->insert($chunk);
                    });
                }
                $this->info("◘ Datos insertados en la tabla " . $tableName);
            } else {
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'  => $type,
                    'descripcion' => 'Traits::ReadExportDataTrait[processFileContent()] => Error al insertar datos en la tabla '.$tableName
                ]);
                return 1;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::ReadExportDataTrait[processFileContent()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}