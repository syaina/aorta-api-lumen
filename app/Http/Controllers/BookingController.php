<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Produk;
use App\User;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', ['except' => [ 
            'store'
        ]]);

        // $this->middleware('user', ['only' => [ 
        //     'create'
        // ]]);
    }

    public function index()
    {
        $getBooking = Booking::all();

        $out = [
            'message' => 'get_booking_success',
            'results' => $getBooking
        ];

        return response()->json($out, 200);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'id_user' => 'required',
                'no_handphone' => 'required',
                'id_produk' => 'required',
                'bukti_transfer' => 'required'
            ]);
            
            $id_user = $request->input('id_user');
            $no_handphone = $request->input('no_handphone');
            $id_produk = $request->input('id_produk');
            $bukti_transfer = $request->input('bukti_transfer');

            $id_booking = IdGenerator::generate([
                'table' => 'booking', 
                'field'=>'id_booking',
                'length' => 10, 
                'prefix' => 'BKN-'
            ]);

            $user = User::where('id_user', $id_user);
            $produk = Produk::where('id_produk', $id_produk);

            if($user->exists() && $produk->exists()) {
                $data = [
                    'id_booking' => $id_booking,
                    'id_user' => $id_user,
                    'no_handphone' => $no_handphone,
                    'id_produk' => $id_produk,
                    'bukti_transfer' => $bukti_transfer
                ];

                $insert = Booking::create($data);

                if ($insert) {
                    $out  = [
                        'message' => 'insert_booking_success',
                        'results' => $data,
                        'code'  => 200
                    ];
                } else {
                    $out  = [
                        'message' => 'insert_booking_failed',
                        'code'   => 422,
                    ];
                }
            } else {
                $out  = [
                    'message' => 'user_or_produk_not_found',
                    'code'   => 422,
                ];
            }  
        }

        return response()->json($out, $out['code']);
    }

    public function update(Request $request) 
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'no_handphone' => 'required',
                'id_produk' => 'required',
                'bukti_transfer' => 'required'
            ]);
            
            $id_booking = $request->input('id_booking');
            $no_handphone = $request->input('no_handphone');
            $id_produk = $request->input('id_produk');
            $bukti_transfer = $request->input('bukti_transfer');

            $data = [
                'id_booking' => $id_booking,
                'no_handphone' => $no_handphone,
                'id_produk' => $id_produk,
                'bukti_transfer' => $bukti_transfer
            ];

            $produk = Produk::where('id_produk', $id_produk);
            $booking = Booking::where('id_booking', $id_booking);

            if(!$booking->exists()) {
                $out = [
                    'message' => 'id_not_found',
                    'code' => 422
                ];
            } elseif (!$produk->exists()) {
                $out = [
                    'message' => 'produk_not_found',
                    'code' => 422
                ];
            } else {
                $update = $booking->update($data);

                if($update) {
                    $out = [
                        'message' => 'update_booking_success',
                        'code' => 200,
                        'result' => $booking->get()
                    ];
                } else {
                    $out = [
                        'message' => 'update_booking_failed',
                        'code' => 404
                    ];
                }
            }

            return response()->json($out, $out['code']);
        }
    }

    public function indexById ($id) {
        $booking = Booking::where('id_booking', $id);

        if ($booking->exists()) {
            $data = $booking->get();

            $out = [
                'message' => 'get_booking_success',
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
        $id_booking = $request->input('id_booking');

        $booking =  Booking::where('id_booking', $id_booking);

        if (!$booking->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $booking->delete();

            $out = [
                'message' => 'delete_booking_success',
                'code' => 200
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function getDelete() 
    {
        $booking = Booking::onlyTrashed()->get();

        $out = [
            'message' => 'get_delete_booking_success',
            'results' => $booking
        ];

        return response()->json($out, 200);
    }

    public function restore(Request $request)
    {
        $id_booking = $request->input('id_booking');
        $booking = booking::onlyTrashed()->where('id_booking', $id_booking);
        
        if (!$booking->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 401
            ];
        } else {
            $booking->restore();

            $out = [
                'message' => 'restore_booking_success',
                'code' => 200
            ];
        }
        
        return response()->json($out, $out['code']);
    }

    public function updateStatus(Request $request)
    {
        $id_booking = $request->input('id_booking');

        $booking = Booking::where('id_booking', $id_booking);

        if(!$booking->exists()) {
            $out = [
                'message' => 'id_not_found',
                'code' => 422
            ];
        } else {
            $update = $booking->update(['status' => 'paid']);

            if($update) {
                $out = [
                    'message' => 'update_booking_status_success',
                    'code' => 200,
                    'result' => $booking->get()
                ];
            } else {
                $out = [
                    'message' => 'update_booking_status_failed',
                    'code' => 404
                ];
            }
        }

        return response()->json($out, $out['code']);
    }
}