<?php

namespace App;

class Message {

//    private $API_KEY = '228632AJpc7prnZC5b72a7c4';
//    private $SENDER_ID = "Gudddeal";

     private $API_KEY = '178956ALikDkzr5R5b7e4b5b';
     private $SENDER_ID = "Gudddeal";
//    private $ROUTE_NO = 4;
//    private $RESPONSE_TYPE = 'json';

    public function sendOTP($mobile) {
        //Your message to send, Adding URL encoding.
        $message = trim("Gudddeal - Your verification code is : ##OTP##");

        //Preparing post parameters
        $postData = array(
            'authkey' => $this->API_KEY,
            'message' => $message,
            'sender' => $this->SENDER_ID,
            'mobiles' => $mobile,
        );

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://control.msg91.com/api/sendotp.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);
        
        //Print error if any
        if ($error) {
            return $error;
        } else {
            return $response;
        }
        
    }

    public function resendOTP($mobile) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/retryotp.php?authkey=$this->API_KEY&mobile=$mobile&retrytype=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response   = curl_exec($curl);
        $error      = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return $error;
        } else {
            return $response;
        }
    }

    public function verifyOTP($mobile, $OTP) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://control.msg91.com/api/verifyRequestOTP.php?authkey=$this->API_KEY&mobile=$mobile&otp=$OTP",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response   = curl_exec($curl);
        $error      = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return $error;
        } else {
            return $response;
        }
    }
}
