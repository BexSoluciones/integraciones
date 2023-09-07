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
                $consultaId = $item['consulta_id'];
                $data = $item['data'];

                $content = '';

                foreach ($data as $clave => $key) {
                    $content .= json_encode(implode($separador, $key), JSON_PRETTY_PRINT) . "\n";
                }
                
                $namefile = strtolower($consultaId) . '.txt';
                
                Storage::disk('local')->put('imports/'.$db.'/planos/'. $namefile, str_replace('"','',$content));
           
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            echo "Ha ocurrido una excepción: " . $e->getMessage();
        }
    }
    
}