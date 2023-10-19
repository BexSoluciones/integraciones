<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait FlatFileTrait {
    
    public function generateFlatFile($dataWS, $db){
        try {
            foreach ($dataWS as $item) {
                $separador = $item['separador'];
                $descripcion = $item['descripcion'];
                $data = $item['data'];
                
                // No tomar en cuenta la columna 'ws_id' de $data
                foreach ($data as &$key) {
                    unset($key['ws_id']);
                }

                $content = '';

                foreach ($data as $clave => $key) {
                    $content .= json_encode(implode($separador, $key), JSON_PRETTY_PRINT) . "\n";
                }
                
                $namefile = strtolower($descripcion ) . '.txt';
                
                Storage::disk('local')->put('imports/'.$db.'/planos/'. $namefile, str_replace('"','',$content));
                $this->info('◘ Archivo '.$descripcion.'.txt guardado con exito');
                $this->info('-------------------------------------------------------------------');
            }
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error (Creación archivo plano): " . $e->getMessage());
        }
    }
    
}