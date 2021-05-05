<?php

namespace App\Http\Controllers;

use App\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class MateriController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', ['only' => [ 
            'store',
            'update',
            'delete',
            'getDelete',
            'restore'
        ]]);
    }

    public function index()
    {
        $getMateri = Materi::all();

        $out = [
            'message' => 'get_materi_success',
            'results' => $getMateri
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'judul_materi' => 'required',
                'url_gambar' => 'required'
            ]);
            
            $judul_materi = $request->input('judul_materi');
            $url_gambar = $request->input('url_gambar');
            $slug = Str::slug($judul_materi, '-');

            $id_materi = IdGenerator::generate([
                'table' => 'materi', 
                'field'=>'id_materi',
                'length' => 10, 
                'prefix' => 'MTR-'
            ]);

            $data = [
                'id_materi' => $id_materi,
                'judul_materi' => $judul_materi,
                'url_gambar' => $url_gambar,
                'slug' => $slug
            ];

            $insert = Materi::create($data);

            if ($insert) {
                $out  = [
                    "message" => "insert_materi_success",
                    "results" => $data,
                    "code"  => 200
                ];
            } else {
                $out  = [
                    "message" => "insert_materi_failed",
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
                'judul_materi' => 'required',
                'url_gambar' => 'required'
            ]);
            
            $id_materi = $request->input('id_materi');
            $judul_materi = $request->input('judul_materi');
            $url_gambar = $request->input('url_gambar');
            $slug = Str::slug($judul_materi, '-');

            $data = [
                'judul_materi' => $judul_materi,
                'url_gambar' => $url_gambar,
                'slug' => $slug
            ];

            $materi = Materi::where('id_materi', $id_materi);

            if(!$materi) {
                $out = [
                    'message' => 'id_not_found',
                    'code' => 422
                ];
            } else {
                $update = $materi->update($data);

                if($update) {
                    $out = [
                        'message' => 'update_materi_success',
                        'code' => 200,
                        'result' => $materi->get()
                    ];
                } else {
                    $out = [
                        'message' => 'update_materi_failed',
                        'code' => 404
                    ];
                }
            }

            return response()->json($out, $out['code']);
        }
    }

    public function indexById ($id) {
        $materi = Materi::where('id_materi', $id);

        if ($materi->exists()) {
            $data = $materi->get();

            $out = [
                'message' => 'get_materi_success',
                'results' => $data,
                'code' => 200,
            ];
        } else {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function delete(Request $request) 
    {
        $id_materi = $request->input('id_materi');

        $materi =  Materi::where('id_materi', $id_materi);

        if (!$materi->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $materi->update(['is_deleted' => 1]);
            $materi->delete();

            $out = [
                'message' => 'delete_materi_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function getDelete() 
    {
        $materi = Materi::onlyTrashed()->get();

        $out = [
            'message' => 'get_delete_materi_success',
            'results' => $materi
        ];

        return response()->json($out, 200);
    }

    public function restore(Request $request)
    {
        $id_materi = $request->input('id_materi');
        $materi = Materi::onlyTrashed()->where('id_materi', $id_materi);
        
        if (!$materi->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $materi->restore();

            $out = [
                'message' => 'restore_materi_success',
                'code' => 200
            ];
        }
        
        return response()->json($out, $out['code']);
    }
}