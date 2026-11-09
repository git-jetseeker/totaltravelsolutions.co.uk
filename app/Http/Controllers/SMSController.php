<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class SMSController extends Controller

{

    //



    public  static  function send_sms($number, $ref){
        return (new \App\Library\functions())->send_sms($number, $ref);

    }

}
