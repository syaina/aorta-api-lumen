<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
            'nama' => 'required',
            'asal_universitas' => 'required',
            'pekerjaan' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $nama = $request->input('nama');
        $asal_universitas = $request->input('asal_universitas');
        $pekerjaan = $request->input('pekerjaan');

        $hash_password = Hash::make($password);

        $data = [
            'email' => $email,
            'password' => $hash_password,
            'nama' => $nama,
            'asal_universitas' =>$asal_universitas,
            'pekerjaan' => $pekerjaan
        ];

        if (User::create($data)) {
            $out = [
                'message' => 'register_success',
                'code' => 201
            ];
        } else {
            $out = [
                'message' => 'register_failed',
                'code' => 404
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function login(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email) -> first();

        if (!$user) {
            $out = [
                'message' => 'login_failed (user not found)',
                'code' => 401,
                'result' => [
                    'token' => null
                ]
            ];

            return response()->json($out, $out['code']);
        }

        if (Hash::check($password, $user->password)) {
            $new_token = $this->generateRandomString();

            $user->update([
                'token' => $new_token
            ]);

            $out = [
                'message' => 'login_success',
                'code' => 200,
                'result' => [
                    'token' => $new_token
                ]
            ];
        } else {
            $out = [
                'message'=> 'login_failed (wrong password)',
                'code' => 401,
                'result' => [
                    'token' => null
                ]
            ];
        }

        return response()->json($out, $out['code']);
    }
    
    function generateRandomString($length=80) 
    {
        $char = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len_char = strlen($char);
        $str = '';
        for ($i=0; $i < $length; $i++) {
            $str .= $char[rand(0, $len_char -1)];
        }

        return $str;
    }
}
