<?php
namespace App\Traits;

use Exception;

use App\Models\Tbl_Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait FlatFileTrait {
    
    public function generateFlatFile($dataWS, $db, $id_importation, $type){
        try {
            foreach ($dataWS as $item) {
                $separador = $item['separador'];
                $descripcion = $item['descripcion'];
                $data = $item['data'];
                
                // No tomar en cuenta la columna 'ws_id' de $data
                foreach ($data as $key) {
                    unset($key['ws_id']);
                }

                $content = '';

                foreach ($data as $clave => $key) {
                    $content .= implode($separador, $key). "\n";
                }
                $content = rtrim($content, "\n");
                $namefile = strtolower($descripcion ) . '.txt';
                
                Storage::disk('local')->append('imports/'.$db.'/planos/'. $namefile, str_replace('"','',$content));
                $this->info('◘ Archivo '.$descripcion.'.txt guardado con exito');
                $this->info('-------------------------------------------------------------------');

                if($db == 'bex_0007'){
                   $sendToSFTP = $this->sendToSFTP($namefile, $content, $id_importation, $type);
                   if($sendToSFTP == 1){
                        return 1;
                   }
                }
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::FlatFileTrait[generateFlatFile()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    private function sendToSFTP($filename, $content, $id_importation, $type) {
        try {
            $ftpHost = 'tornillosatlanticosas.bexsoluciones.com'; // Reemplaza con la dirección del servidor FTP
            $ftpUser = 'bexmovil_260'; // Reemplaza con tu nombre de usuario FTP
            $ftpPass = 'V3qAOFDB'; // Reemplaza con tu contraseña FTP

            // Establecer conexión FTP
            $connId = ftp_connect($ftpHost);
            if ($connId) {
                // Iniciar sesión FTP
                $login = ftp_login($connId, $ftpUser, $ftpPass);
                if ($login) {
                    // Cambiar al directorio remoto según tu estructura
                    $remoteDirectory = '/var/www/bexmovil/importadores/tornillosatlanticosas/planos/tornillosatlanticosas';
                    ftp_chdir($connId, $remoteDirectory);

                    // Subir archivo al servidor FTP
                    $upload = ftp_put($connId, $filename, $content, FTP_ASCII);

                    if ($upload) {
                        $this->info('◘ Archivo ' . $filename . ' enviado al servidor FTP con éxito');
                    } else {
                        Tbl_Log::create([
                            'id_table'    => $id_importation,
                            'type'        => $type,
                            'descripcion' => 'Traits::FlatFileTrait[sendToSFTP()] => Error al subir archivo al servidor FTP'
                        ]);
                        return 1;
                    }

                    // Cerrar sesión FTP
                    ftp_close($connId);
                }
            } else {
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Traits::FlatFileTrait[sendToSFTP()] => Error al conectar al servidor FTP'
                ]);
                return 1;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::FlatFileTrait[sendToSFTP()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

}
    
