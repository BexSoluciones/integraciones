<?php
namespace App\Traits;

use Exception;

use App\Models\Tbl_Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait FlatFileTrait {
    
    public function generateFlatFile($dataWS, $db, $id_importation, $type) {
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
                $namefile = strtolower($descripcion) . '.txt';
                
                Storage::disk('local')->put('imports/'.$db.'/planos/'. $namefile, str_replace('"','',$content));
                $this->info('◘ Archivo '.$descripcion.'.txt guardado con éxito');

                if ($db == 'bex_0007') {
                    $sendToSFTP = $this->sendToSFTP($namefile, $id_importation, $type, $db);
                    if ($sendToSFTP == 1) {
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

    private function sendToSFTP($filename, $id_importation, $type, $db) {
        try {
            $ftpHost = 'tornillosatlanticosas.bexsoluciones.com'; // Dirección del servidor FTP
            $ftpUser = 'bexmovil_260'; // Nombre de usuario FTP
            $ftpPass = 'V3qAOFDB'; // Contraseña FTP
            $localFilePath = storage_path('app/imports/'.$db.'/planos/'.$filename);

            // Establecer conexión FTP
            $connId = ftp_connect($ftpHost);
            if ($connId) {
                // Iniciar sesión FTP
                $login = ftp_login($connId, $ftpUser, $ftpPass);
                if ($login) {
                    $remoteDirectory = '/';
                    if (ftp_chdir($connId, $remoteDirectory)) {
                        // Subir el archivo al servidor FTP
                        $upload = ftp_put($connId, $filename, $localFilePath, FTP_ASCII);
                        if ($upload) {
                            $this->info("Archivo $filename cargado con éxito");
                        } else {
                            Tbl_Log::create([
                                'id_table'    => $id_importation,
                                'type'        => $type,
                                'descripcion' => "Traits::FlatFileTrait[sendToSFTP()] => Error al cargar el archivo $filename al servidor FTP"
                            ]);
                            return 1;
                        }
                    } else {
                        Tbl_Log::create([
                            'id_table'    => $id_importation,
                            'type'        => $type,
                            'descripcion' => 'Traits::FlatFileTrait[sendToSFTP()] => No se pudo cambiar al directorio remoto $remoteDirectory'
                        ]);
                        return 1;
                    }

                    // Cerrar sesión FTP
                    ftp_close($connId);
                } else {
                    Tbl_Log::create([
                        'id_table'    => $id_importation,
                        'type'        => $type,
                        'descripcion' => 'Traits::FlatFileTrait[sendToSFTP()] => Error al iniciar sesión FTP'
                    ]);
                    return 1;
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
    
