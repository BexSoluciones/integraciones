<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FlatFileController extends Controller
{
    public function download(Request $request){
        try{
            $rutaArchivo = 'export/bex_0002/pedidos_txt/' . $request->numero.'.txt';
            $rutaCompleta = storage_path('app/public/' . $rutaArchivo);
            if (file_exists($rutaCompleta)) {
                 // URL pública del archivo
                $urlArchivo = Storage::url($rutaArchivo);
                // Agregar el dominio a la URL
                $urlCompleta = url($urlArchivo);
                return response()->json(['status' => 200, 'response' => $urlCompleta]);
            } else {
                return response()->json(['status' => 200, 'response' => 'No se encontro archivo '.$request->numero.'.txt']);
            }
        } catch (\Exception $e) {
            Log::error('Error uploadOrder: ' . $e->getMessage());
            return  'Error uploadOrder: ' . $e->getMessage();
        }
    }
}
