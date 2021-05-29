<?php

namespace App\Http\Controllers;

use App\Bab;
use App\Soal;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class soalController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', ['except' => [ 
            'index',
            'indexById',
            'indexByBab',
            'indexByMateri'
        ]]);
    }

    public function index()
    {
        $getSoal = Soal::all();

        $out = [
            'message' => 'get_soal_success',
            'results' => $getSoal
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'id_bab' => 'required',
                'soal' => 'required',
                'jawaban' => 'required',
                'url_gambar' => 'required',
                'pil1' => 'required',
                'pil2' => 'required',
                'pil3' => 'required',
                'pil4' => 'required',
                'pil5' => 'required'
            ]);
            
            $id_bab = $request->input('id_bab');
            $soal = $request->input('soal');
            $jawaban = $request->input('jawaban');
            $url_gambar = $request->input('url_gambar');
            $pil1 = $request->input('pil1');
            $pil2 = $request->input('pil2');
            $pil3 = $request->input('pil3');
            $pil4 = $request->input('pil4');
            $pil5 = $request->input('pil5');

            $id_materi = Bab::where('id_bab', $id_bab)->first()->id_materi;

            $id_soal = IdGenerator::generate([
                'table' => 'soal', 
                'field'=>'id_soal',
                'length' => 11, 
                'prefix' => 'SOAL-'
            ]);

            $bab = Bab::where('id_bab', $id_bab);

            if($bab->exists()) {
                $data = [
                    'id_soal' => $id_soal,
                    'id_bab' => $id_bab,
                    'id_materi' => $id_materi,
                    'soal' => $soal,
                    'pil1' => $pil1,
                    'pil2' => $pil2,
                    'pil3' => $pil3,
                    'pil4' => $pil4,
                    'pil5' => $pil5,
                    'jawaban' => $jawaban,
                    'url_gambar' => $url_gambar
                ];
    
                $insert = Soal::create($data);
    
                if ($insert) {
                    $out  = [
                        "message" => "insert_soal_success",
                        "results" => $data,
                        "code"  => 200
                    ];
                } else {
                    $out  = [
                        "message" => "insert_soal_failed",
                        "code"   => 401,
                    ];
                }
            } else {
                $out  = [
                    "message" => "id_bab_not_found",
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
                'id_soal' => 'required',
                'id_bab' => 'required',
                'soal' => 'required',
                'jawaban' => 'required',
                'url_gambar' => 'required',
                'pil1' => 'required',
                'pil2' => 'required',
                'pil3' => 'required',
                'pil4' => 'required',
                'pil5' => 'required'
            ]);
            
            $id_soal = $request->input('id_soal');
            $id_bab = $request->input('id_bab');
            $soal = $request->input('soal');
            $jawaban = $request->input('jawaban');
            $url_gambar = $request->input('url_gambar');
            $pil1 = $request->input('pil1');
            $pil2 = $request->input('pil2');
            $pil3 = $request->input('pil3');
            $pil4 = $request->input('pil4');
            $pil5 = $request->input('pil5');

            $soal = Soal::where('id_soal', $id_soal);
            $bab = Bab::where('id_bab', $id_bab);

            if(!$soal->exists()) {
                $out = [
                    'message' => 'id_not_found',
                    'code' => 422
                ];
            } else {
                if($bab->exists()) {
                    $id_materi = Bab::where('id_bab', $id_bab)->first()->id_materi;

                    $data = [
                        'id_bab' => $id_bab,
                        'id_materi' => $id_materi,
                        'soal' => $soal,
                        'pil1' => $pil1,
                        'pil2' => $pil2,
                        'pil3' => $pil3,
                        'pil4' => $pil4,
                        'pil5' => $pil5,
                        'jawaban' => $jawaban,
                        'url_gambar' => $url_gambar,
                    ];

                    $update = $soal->update($data);

                    if($update) {
                        $out = [
                            'message' => 'update_soal_success',
                            'code' => 200,
                            'result' => $soal->get()
                        ];
                    } else {
                        $out = [
                            'message' => 'update_soal_failed',
                            'code' => 422
                        ];
                    }

                } else {
                    $out = [
                        'message' => 'id_bab_not_found',
                        'code' => 404
                    ];
    
                }
            }

            return response()->json($out, $out['code']);
        }
    }

    public function indexById ($id) {
        $soal = Soal::where('id_soal', $id);

        if ($soal->exists()) {
            $data = $soal->get();

            $out = [
                'message' => 'get_soal_success',
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

    public function indexByBab ($id) {
        $soal = soal::where('id_bab', $id);

        if ($soal->exists()) {
            $data = $soal->get();

            $out = [
                'message' => 'get_soal_success',
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
        $soal = soal::where('id_materi', $id);

        if ($soal->exists()) {
            $data = $soal->get();

            $out = [
                'message' => 'get_soal_success',
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
        $id_soal = $request->input('id_soal');

        $soal =  Soal::where('id_soal', $id_soal);

        if (!$soal->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $soal->delete();

            $out = [
                'message' => 'delete_soal_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function getDelete() 
    {
        $soal = Soal::onlyTrashed()->get();

        $out = [
            'message' => 'get_delete_soal_success',
            'results' => $soal
        ];

        return response()->json($out, 200);
    }

    public function restore(Request $request)
    {
        $id_soal = $request->input('id_soal');
        $soal = Soal::onlyTrashed()->where('id_soal', $id_soal);
        
        if (!$soal->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $soal->restore();

            $out = [
                'message' => 'restore_soal_success',
                'code' => 200
            ];
        }
        
        return response()->json($out, $out['code']);
    }
}