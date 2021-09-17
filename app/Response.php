<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Response extends Model
{
    public static function newResponseCount(){
        $receiver_id = Auth::user()->id;
        $newResponseCount = Response::where(['receiver_id' => $receiver_id, 'seen'=> 0])->count();
        return $newResponseCount;
    }
}
