<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    
    public function login(Request $request) {

        try {
            $user = User::where('name', $request->name)->first();
            
            if(!$user){
                return response()->json(['status' => 404, 'error' => 'Este usuario no existe']);
            }

            $credentials = $request->only('name', 'password');
            if (Auth::attempt($credentials)) {
                // El usuario ha sido autenticado con éxito
                $user = Auth::user();

                // Generar un nuevo token de acceso
                $token = $user->createToken('token-name')->plainTextToken;

                // Puedes devolver el token como respuesta
                return response()->json(['status' => 200, 'token' => $token]);
            }
            return response()->json(['status' => 401, 'error' => 'Las credenciales no son correctas']);

        } catch (\Exception $e) {
            return response()->json(['status' => 404, 'error' => 'Este usuario no existe']);
        }
    }


    public function prueba(){
        return 'hola';
    }
}
