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
                    $content .= implode($separador, $key). "\n";
                }
                $content = rtrim($content, "\n");
                $namefile = strtolower($descripcion ) . '.txt';
                
                Storage::disk('local')->append('imports/'.$db.'/planos/'. $namefile, str_replace('"','',$content));
                $this->info('◘ Archivo '.$descripcion.'.txt guardado con exito');
                $this->info('-------------------------------------------------------------------');

                if($db == 'bex_0007'){
                    $this->sendToSFTP($namefile, $content);
                }
            }
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error (Creación archivo plano): " . $e->getMessage());
        }
    }

    private function sendToFTP($filename, $content) {
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
                        $this->error('Error al subir archivo al servidor FTP');
                    }

                    // Cerrar sesión FTP
                    ftp_close($connId);
                } else {
                    $this->error('Error al conectar al servidor FTP');
                }
            } else {
                $this->error('Error al establecer conexión FTP');
            }
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error (Envío a FTP): " . $e->getMessage());
        }
    }

}
    
