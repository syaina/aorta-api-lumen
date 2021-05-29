<?php

namespace App\Http\Controllers;

use App\Bab;
use App\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BabController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', ['except' => [ 
            'index',
            'indexById'
        ]]);
    }

    public function index()
    {
        $getBab = Bab::all();

        $out = [
            'message' => 'get_bab_success',
            'results' => $getBab
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'id_materi' => 'required',
                'judul_bab' => 'required',
                'url_gambar' => 'required'
            ]);
            
            $id_materi = $request->input('id_materi');
            $judul_bab = $request->input('judul_bab');
            $url_gambar = $request->input('url_gambar');
            $slug = Str::slug($judul_bab, '-');

            $id_bab = IdGenerator::generate([
                'table' => 'bab', 
                'field'=>'id_bab',
                'length' => 10, 
                'prefix' => 'BAB-'
            ]);

            $materi = Materi::where('id_materi', $id_materi);

            if($materi->exists()) {
                $data = [
                    'id_bab' => $id_bab,
                    'id_materi' => $id_materi,
                    'judul_bab' => $judul_bab,
                    'url_gambar' => $url_gambar,
                    'slug' => $slug
                ];
    
                $insert = bab::create($data);
    
                if ($insert) {
                    $out  = [
                        "message" => "insert_bab_success",
                        "results" => $data,
                        "code"  => 200
                    ];
                } else {
                    $out  = [
                        "message" => "insert_bab_failed",
                        "code"   => 422,
                    ];
                }
            } else {
                $out  = [
                    "message" => "id_materi_not_found",
                    "code"   => 422,
                ];
            }

            return response()->json($out, $out['code']);
        }
    }

    public function update(Request $request) 
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'id_bab' => 'required',
                'id_materi' => 'required',
                'judul_bab' => 'required',
                'url_gambar' => 'required'
            ]);
            
            $id_bab = $request->input('id_bab');
            $id_materi = $request->input('id_materi');
            $judul_bab = $request->input('judul_bab');
            $url_gambar = $request->input('url_gambar');
            $slug = Str::slug($judul_bab, '-');

            $data = [
                'judul_bab' => $judul_bab,
                'url_gambar' => $url_gambar,
                'slug' => $slug,
                'id_materi' => $id_materi
            ];

            $bab = Bab::where('id_bab', $id_bab);
            $materi = Materi::where('id_materi', $id_materi);

            if(!$bab->exists()) {
                $out = [
                    'message' => 'id_not_found',
                    'code' => 422
                ];
            } else {
                if($materi->exists()) {
                    $update = $bab->update($data);

                    if($update) {
                        $out = [
                            'message' => 'update_bab_success',
                            'code' => 200,
                            'result' => $bab->get()
                        ];
                    } else {
                        $out = [
                            'message' => 'update_bab_failed',
                            'code' => 404
                        ];
                    }

                } else {
                    $out = [
                        'message' => 'id_materi_not_found',
                        'code' => 404
                    ];
    
                }
            }

            return response()->json($out, $out['code']);
        }
    }

    public function indexById ($id) {
        $bab = Bab::where('id_bab', $id);

        if ($bab->exists()) {
            $data = $bab->get();

            $out = [
                'message' => 'get_bab_success',
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

    public function indexByMateri ($id) {
        $bab = Bab::where('id_materi', $id);

        if ($bab->exists()) {
            $data = $bab->get();

            $out = [
                'message' => 'get_bab_success',
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
        $id_bab = $request->input('id_bab');

        $bab =  Bab::where('id_bab', $id_bab);

        if (!$bab->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $bab->delete();

            $out = [
                'message' => 'delete_bab_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function getDelete() 
    {
        $bab = Bab::onlyTrashed()->get();

        $out = [
            'message' => 'get_delete_bab_success',
            'results' => $bab
        ];

        return response()->json($out, 200);
    }

    public function restore(Request $request)
    {
        $id_bab = $request->input('id_bab');
        $bab = Bab::onlyTrashed()->where('id_bab', $id_bab);
        
        if (!$bab->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $bab->restore();

            $out = [
                'message' => 'restore_bab_success',
                'code' => 200
            ];
        }
        
        return response()->json($out, $out['code']);
    }
}