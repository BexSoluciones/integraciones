<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\DB;
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
                        $this->info("◘ Archivo plano coincide con el modelo: $tableName");
                        $this->processFileContent($modelClass, $content);
                    }
                }
            }
            $this->info('◘ Proceso de exportacion finalizado en la BD '.$db);
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error: " . $e->getMessage());
        }
    }

    private function processFileContent($modelClass, $content) {
        
        $modelInstance = new $modelClass();
        $columnsModelo = $modelInstance->getFillable();
        
        if ($content === false) {
            $this->error("No se pudo leer el archivo plano ".$modelInstance);
            return;
        }
        
        // Split the content into lines
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            // Split each line into columns using the |
            $columns = explode("|", $line);
            //dd($columns);
            $dataToInsert = [];
            
            // Check if there are enough columns for the insertion
            if (count($columns) >= count($columnsModelo)) {
                // Construct an associative array of data for insertion
                $dataToInsert = array_combine($columnsModelo, array_slice(array_map('trim', $columns), 0, count($columnsModelo)));
                
                // Insert the data into the corresponding model
                if ($modelInstance) {
                    //Insert data in the models
                    $modelInstance->create($dataToInsert);
                    $this->info("◘ Datos insertados en el modelo " . get_class($modelInstance));
                } else {
                    $this->error("Error al insertar datos en la línea:". $line);
                }
            }
        }
    }
}