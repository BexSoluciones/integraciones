<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait ReadExportDataTrait {
    
    public function readFlatFile($db) {
        try {
            //Folder of Models
            $baseNamespace = 'App\\Models\\'.ucfirst($db).'\\';
            //All models
            $modelFiles = File::files(app_path('Models/'.ucfirst($db)));
            $availableModels = [];

            foreach ($modelFiles as $modelFile) {
                //Route of model
                $routeName = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
                if (class_exists($routeName)) {
                    //extracts the model name and stores it in the $availableModels array
                    $availableModels[$routeName] = (new $routeName())->getTable();
                }
            }
            
            //Route of flat file
            $folderPath = storage_path("app/imports/$db/planos");
            $txtFiles = glob("$folderPath/*.txt");
           
            foreach ($txtFiles as $txtFile) {
                
                $content = file_get_contents($txtFile);
                $filenameWithoutExtension = pathinfo($txtFile, PATHINFO_FILENAME);
                
                foreach ($availableModels as $modelClass => $tableName) {
                    if ($filenameWithoutExtension === $tableName) {
                        $this->info("◘ El archivo plano $filenameWithoutExtension coincide con el modelo: $tableName");
                        $this->processFileContent($modelClass, $content, $tableName);
                    }
                }
            }
            $this->info('◘ Proceso de exportacion finalizado en la BD del inquilino '.$db);
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error: " . $e->getMessage());
        }
    }

    private function processFileContent($modelClass, $content, $tableName) {
        try{
            $modelInstance = new $modelClass();
            $columnsModelo = $modelInstance->getFillable();
            $autoIncrement = 1;
        
            if ($content === false) {
                $this->error("No se pudo leer el archivo plano " . $modelInstance);
                return;
            }
        
            // Split the content into lines
            $lines = explode("\n", $content);
            $dataToInsert = [];
        
            foreach ($lines as $line) {
                // Split each line into columns using the |
                $columns = explode("|", $line);
        
                // Construct an associative array of data for insertion
                $rowData = [];
        
                // Fill rowData with values from $columns, up to the number of fillable columns
                for ($i = 0; $i < count($columns); $i++) {
                    if($tableName == 't05_bex_clientes'){
                        $j = $i+1;
                    } else {
                        $j = $i; 
                    }
                    $rowData[$columnsModelo[$j]] = isset($columns[$i]) ? trim($columns[$i]) : null;
                }
        
                // Insert the data into the array to be bulk-inserted
                if ($tableName == 't05_bex_clientes') {
                    //Inserta un autoincrement
                    $rowData['consecutivo'] = $autoIncrement++;
                }
        
                $dataToInsert[] = array_map('utf8_encode', $rowData);
            }
        
            // Bulk insert the data into the corresponding model
            if ($modelInstance && !empty($dataToInsert)) {
                $chunks = array_chunk($dataToInsert, 1000); // Divide en lotes de 1000 registros
                foreach ($chunks as $chunk) {
                    $modelInstance->insert($chunk);
                }
                $this->info("◘ Datos insertados en la tabla " . $tableName);
            } else {
                $this->error("Error al insertar datos en la tabla " . $tableName);
            }
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error: " . $e->getMessage());
        }
    }
}