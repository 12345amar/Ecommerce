<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class Mails extends Model
{
    public static function sendToMail($sub, $to, $array)
    {     
        $data['subject']=$sub;     
        $data['data']=$array;        
        Mail::to($to)->send(new SendMail($data));   
        return true;       
    }
}
