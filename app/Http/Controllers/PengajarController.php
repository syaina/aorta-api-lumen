<?php

namespace App\Http\Controllers;

use App\Pengajar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class PengajarController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', ['only' => [ 
            'store',
            'update',
            'delete'
        ]]);
    }

    public function index()
    {
        $getPengajar = Pengajar::all();

        $out = [
            'message' => 'get_pengajar_success',
            'results' => $getPengajar
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'url_gambar' => 'required',
                'nama' => 'required',
                'deskripsi' => 'required'
            ]);
            
            $url_gambar = $request->input('url_gambar');
            $nama = $request->input('nama');
            $deskripsi = $request->input('deskripsi');

            $data = [
                'url_gambar' => $url_gambar,
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'is_deleted' => 0
            ];

            $insert = Pengajar::create($data);

            if ($insert) {
                $out  = [
                    "message" => "insert_pengajar_success",
                    "results" => $data,
                    "code"  => 200
                ];
            } else {
                $out  = [
                    "message" => "insert_pengajar_failed",
                    "results" => $data,
                    "code"   => 404,
                ];
            }
        }

        return response()->json($out, $out['code']);
    }

    public function update(Request $request) 
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'url_gambar' => 'required',
                'nama' => 'required',
                'deskripsi' => 'required',
            ]);
            
            $id = $request->input('id');
            $url_gambar = $request->input('url_gambar');
            $nama = $request->input('nama');
            $deskripsi = $request->input('deskripsi');

            $pengajar = Pengajar::find($id);

            $data = [
                'url_gambar' => $url_gambar,
                'nama' => $nama,
                'deskripsi' => $deskripsi
            ];

            $update = $pengajar->update($data);

            if($update) {
                $out = [
                    'message' => 'update_pengajar_success',
                    'code' => 200,
                    'result' => $data
                ];
            } else {
                $out = [
                    'message' => 'update_pengajar_failed',
                    'code' => 404
                ];
            }

            return response()->json($out, $out['code']);
        }
    }

    public function delete(Request $request) 
    {
        $id = $request->input('id');

        $pengajar =  Pengajar::find($id);

        if (!$pengajar) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $pengajar->update(['is_deleted' => 1]);
            $out = [
                'message' => 'delete_pengajar_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }
}