<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;



class SMSController extends Controller

{

    //



    public  static  function send_sms($number, $ref){

        $url = "www.voodooSMS.com/vapi/server/sendSMS";

        $getString  ="?dest=".$number."&";

        $getString .="orig=Flyparkplus&";

        $getString .="msg=".urlencode("Thank you for choosing Total Travel Solutions. Your booking with Ref# ".$ref." is confirmed, for arrival & departure procedure please check your email.")."&";

        $getString .="pass=w2imbg6&";

        $getString .="uid=flyparkplus&";

        $getString .="validity=1&";

        $getString .="format=xml&";

        $getString .="cc=44";



        $ch = curl_init();

        //set the url, GET data

        curl_setopt($ch, CURLOPT_URL, $url.$getString);

        curl_setopt($ch, CURLOPT_HTTPGET,1); //default

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        //execute post

        $xml = curl_exec($ch);

        $xml = simplexml_load_string($xml);

        $result = json_decode(json_encode((array)$xml));

        return $result->result;

        curl_close($ch);

    }

}

