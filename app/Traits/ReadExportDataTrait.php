<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait ReadExportDataTrait {
    
    public function readFlatFile($db){
        try {
           
            // Directorio donde se encuentran tus modelos (generalmente "app" en un proyecto Laravel)
            $directorioModelos = app_path('models');

            // Obtener una lista de archivos en el directorio de modelos
            $archivosModelos = File::files($directorioModelos);

            // Recorrer los archivos y obtener los nombres de los modelos
            $modelos = [];
            foreach ($archivosModelos as $archivoModelo) {
                $nombreArchivo = $archivoModelo->getFilename();
                $nombreModelo = pathinfo($nombreArchivo, PATHINFO_FILENAME);

                // Verificar si la clase es un modelo de Eloquent
                if (class_exists($nombreModelo) && is_subclass_of($nombreModelo, 'Illuminate\Database\Eloquent\Model')) {
                    $modelos[] = $nombreModelo;
                }
            }
            $jsonResult = json_encode($modelos, JSON_PRETTY_PRINT);
            $this->info($jsonResult);
            dd('parar');

            // Ruta de la carpeta que deseas leer
            $carpeta = storage_path('app/imports/' . $db . '/planos');
            
            // Obtener la lista de archivos en la carpeta
            $archivos = scandir($carpeta);

            // Iterar sobre la lista de archivos
            foreach ($archivos as $archivo) {
                // Ignorar las entradas "." y ".."
                if ($archivo === "." || $archivo === "..") {
                    continue;
                }

                // Obtener la información de la ruta del archivo
                $infoArchivo = pathinfo($archivo);

                // Verificar si la extensión es .txt
                if (!isset($infoArchivo['extension']) || $infoArchivo['extension'] !== 'txt') {
                    continue;
                }

                
            }

            dd('parar');











            // Obtener todas las clases cargadas en la aplicación una vez
            $baseNamespace = 'App\\Models\\Pandapan\\'; // Ajusta el espacio de nombres de tus modelos
            $modelFiles = File::files(app_path('Models/Pandapan'));
            
            // Crear un diccionario de modelos disponibles
            $modelosDisponibles = [];

            // Iterar sobre las clases cargadas y crear instancias
            foreach ($modelFiles as $modelFile) {
                $className = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
                
                // Verificar si la clase existe
                if (class_exists($className)) {
                    $modelosDisponibles[$className] = new $className();
                }
            }
           
            // Iterar sobre la lista de archivos
            foreach ($archivos as $archivo) {
                // Ignorar las entradas "." y ".."
                if ($archivo === "." || $archivo === "..") {
                    continue;
                }
                ;
                // Ruta completa del archivo
                $rutaArchivo = $carpeta . DIRECTORY_SEPARATOR . $archivo;
                
                // Verificar si el elemento es un archivo (no es una carpeta)
                if (!is_file($rutaArchivo)) {
                    continue;
                }

                // Obtener la información de la ruta
                $infoArchivo = pathinfo($rutaArchivo);
                
                // Verificar la extensión del archivo (en este caso, .txt)
                if (!isset($infoArchivo['extension']) || $infoArchivo['extension'] !== 'txt') {
                    continue;
                }

                // Leer el contenido del archivo
                $contenido = file_get_contents($rutaArchivo);
                
                // Verificar si se pudo leer el archivo
                if ($contenido === false) {
                    echo "No se pudo leer el archivo $archivo.\n";
                    continue;
                }

                // Dividir el contenido en líneas
                $lineas = explode("\n", $contenido);
                
                // Iterar sobre las líneas
                foreach ($lineas as $linea) {
                    // Dividir cada línea en columnas usando el carácter |
                    $columnas = explode("|", $linea);
                    
                    // Obtener el nombre base del archivo (sin extensión)
                    $nombreBaseArchivo = pathinfo($archivo, PATHINFO_FILENAME);

                    // Variables para el modelo y datos
                    $modeloEncontrado = null;
                    $datosParaInsertar = [];

                    // Intentar encontrar un modelo correspondiente
                    foreach ($modelosDisponibles as $className => $modelo) {
                        $columnasModelo = $modelo->getFillable();
                        
                        // Verificar si hay suficientes columnas para la inserción
                        if (count($columnas) >= count($columnasModelo)) {
                            $modeloEncontrado = $modelo;
                            
                            $datosParaInsertar = array_combine($columnasModelo, array_slice(array_map('trim', $columnas), 0, count($columnasModelo)));
                            break; // Salir del bucle después de encontrar un modelo adecuado
                        }
                    }

                    /*
                    // Insertar los datos en el modelo correspondiente
                    if ($modeloEncontrado) {
                        $modeloEncontrado->create($datosParaInsertar);
                        echo "Datos insertados en la base de datos usando el modelo " . get_class($modeloEncontrado) . ".\n";
                    } else {
                        echo "No se encontró un modelo adecuado para la línea: $linea.\n";
                    }*/
                }
            }

        } catch (\Exception $e) {
            $this->error("Ha ocurrido export: " . $e->getMessage());
        }
    }
    
}