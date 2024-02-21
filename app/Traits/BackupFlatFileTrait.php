<?php
namespace App\Traits;

use Exception;

use App\Models\Tbl_Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait BackupFlatFileTrait {
    
    public function backupFlatFile($db, $estado, $id_importation, $type){
        try {
            if($estado == true){
                $flatFilesRoute = 'imports/'.$db.'/planos';
                $backupRoute = 'imports/'.$db.'/planos/backup';
            }else{
                $flatFilesRoute = 'imports/'.$db.'/planos/backup';
                $backupRoute = 'imports/'.$db.'/planos';
            }
            
            //List flat file 
            $files = Storage::files($flatFilesRoute);
            
            foreach ($files as $file) {
                // Check if the file is a .txt file
                if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                    // Gets the file name
                    $fileName = pathinfo($file, PATHINFO_BASENAME);
                    // Move the file to the destination folder
                    Storage::move($file, $backupRoute.'/'.$fileName);

                    //dd(storage_path($backupRoute.'/'.$fileName));
                    chmod(storage_path($backupRoute), 0777);
                }
            }
            
            if($estado == true){
                $this->info('◘ Copia de seguridad archivos planos '.$db.' realizada con exito');
            }else{
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Traits::BackupFlatFileTrait[backupFlatFile()] => Ha ocurrido un error, por lo tanto se restauro la copia de seguridad archivos planos '.$db
                ]);
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::BackupFlatFileTrait[backupFlatFile()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
    
}