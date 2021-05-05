<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $getUser = User::OrderBy("id", "DESC")->paginate(50);

        $out = [
            'message' => 'get_user_success',
            'results' => $getUser
        ];

        return response()->json($out, 200);
    }
}