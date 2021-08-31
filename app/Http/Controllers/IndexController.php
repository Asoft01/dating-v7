<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        // $recent_users = User::get();
        // $recent_users = User::with('details')->limit(3)->orderBy('id', 'Desc')->get();
        // $recent_users = User::with('details')->with('photos')->orderBy('id', 'Desc')->get();
        $recent_users = User::with('details')->with('photos')->orderBy('admin', '!=', '1')->get();
        $recent_users = json_decode(json_encode($recent_users));
        // echo "<pre>"; print_r($recent_users); die;
        return view('index')->with(compact('recent_users'));
    }
}
