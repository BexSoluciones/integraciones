<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait BackupFlatFileTrait {
    
    public function backupFlatFile($db, $estado){
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
                }
            }
            if($estado == true){
                $this->info('◘ Copia de seguridad archivos planos '.$db.' realizada con exito');
            }else{
                $this->info('◘ Se restauro la copia de seguridad archivos planos '.$db);
            }
            return true;
        } catch (\Exception $e) {
            $this->info("Ha ocurrido un error al momento de crear copia de seguridad archivos TXT: " . $e->getMessage());
        }
    }
    
}