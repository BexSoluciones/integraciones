<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tbl_Log;

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
                // Se autentica el usuario
                $user = Auth::user();

                // Generar un nuevo token de acceso
                $token = $user->createToken('token-name')->plainTextToken;

                return response()->json(['status' => 200, 'token' => $token]);
            }
            return response()->json(['status' => 401, 'error' => 'Las credenciales no son correctas']);

        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Controller/Api::AuthController[login()] => '.$e->getMessage()
            ]);
        }
    }
}
