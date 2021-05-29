<?php

namespace App\Http\Controllers;

use App\Produk;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProdukController extends Controller
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
        $getProduk = Produk::all();

        $out = [
            'message' => 'get_produk_success',
            'results' => $getProduk
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'produk' => 'required'
            ]);
            
            $produk = $request->input('produk');

            $id_produk = IdGenerator::generate([
                'table' => 'produk', 
                'field'=>'id_produk',
                'length' => 10, 
                'prefix' => 'PRD-'
            ]);

            $data = [
                'id_produk' => $id_produk,
                'produk' => $produk
            ];

            $insert = Produk::create($data);

            if ($insert) {
                $out  = [
                    'message' => 'insert_produk_success',
                    'results' => $data,
                    'code'  => 200
                ];
            } else {
                $out  = [
                    'message' => 'insert_produk_failed',
                    'code'   => 404,
                ];
            }
        }

        return response()->json($out, $out['code']);
    }

    public function update(Request $request) 
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'id_produk' => 'required',
                'produk' => 'required'
            ]);
            
            $id_produk = $request->input('id_produk');
            $produk = $request->input('produk');

            $data = [
                'produk' => $produk
            ];

            $produk = Produk::where('id_produk', $id_produk);

            if(!$produk->exists()) {
                $out = [
                    'message' => 'id_not_found',
                    'code' => 422
                ];
            } else {
                $update = $produk->update($data);

                if($update) {
                    $out = [
                        'message' => 'update_produk_success',
                        'code' => 200,
                        'result' => $produk->get()
                    ];
                } else {
                    $out = [
                        'message' => 'update_produk_failed',
                        'code' => 404
                    ];
                }
            }

            return response()->json($out, $out['code']);
        }
    }

    public function indexById ($id) {
        $produk = Produk::where('id_produk', $id);

        if ($produk->exists()) {
            $data = $produk->get();

            $out = [
                'message' => 'get_produk_success',
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
        $id_produk = $request->input('id_produk');

        $produk =  Produk::where('id_produk', $id_produk);

        if (!$produk->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $produk->delete();

            $out = [
                'message' => 'delete_produk_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function getDelete() 
    {
        $produk = Produk::onlyTrashed()->get();

        $out = [
            'message' => 'get_delete_produk_success',
            'results' => $produk
        ];

        return response()->json($out, 200);
    }

    public function restore(Request $request)
    {
        $id_produk = $request->input('id_produk');
        $produk = Produk::onlyTrashed()->where('id_produk', $id_produk);
        
        if (!$produk->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $produk->restore();

            $out = [
                'message' => 'restore_produk_success',
                'code' => 200
            ];
        }
        
        return response()->json($out, $out['code']);
    }
}