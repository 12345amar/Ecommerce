<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Message;
use App\Mails;

class UserController extends Controller {

    public $successStatus = 200;
    
    /*
     * Send OTP on mobile
     * 
     * @param  int  $mobile
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile'    => 'required|digits:10'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        $input = $request->all();        
        $msgObj     = new Message();
        $result     = $msgObj->sendOTP($input['mobile']);
        $resultObj  = json_decode($result);
        if($resultObj->type == 'success') {
            $response['result']     = $resultObj->type;
            $response['message']    = 'OTP has been sent to your registered mobile number.';
        } else {
            $response['result']     = $resultObj->type;
            $response['message']    = $resultObj->message;
        }
        
        return response()->json($response);
    }
    
    /*
     * Resend OTP on mobile
     * 
     * @param  int  $mobile
     * @return \Illuminate\Http\Response
     */
    public function resendOTP(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile'    => 'required|digits:10',
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        $input = $request->all();
        
        $msgObj     = new Message();
        $result     = $msgObj->resendOTP($input['mobile']);
        $resultObj  = ($result);
        
        if($resultObj->type == 'success') {
            $response['result']     = $resultObj->type;
            $response['message']    = 'OTP has been re-sent to your registered mobile number.';
        } else {
            $response['result']     = $resultObj->type;
            $response['message']    = $resultObj->message;
        }
        
    }
    
    /*
     * Resend OTP on mobile
     * 
     * @param  int  $mobile
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile'    => 'required|digits:10',
            'otp'       => 'required|digits:4'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        $input = $request->all();        
        $msgObj = new Message();
        $result = $msgObj->verifyOTP($input['mobile'], $input['otp']);
        
        $resultObj = json_decode($result);        
        if($resultObj->type == 'success') {
            $userModel  = new User();
            $userInfo = $userModel->getUserByMobile($input['mobile']);
            if($userInfo) {
                $user      = User::find($userInfo->id);
                $txn=User::primeUserTxn($userInfo->id);
                $response['result']     = $resultObj->type;                
                $response['is_register']= 1;
                $response['message']    = 'Logged in successfully';
                $response['token']      = $user->createToken('MyApp')->accessToken;
                $response['data']       = User::getUserProfile($user);
                $response['data']['prime_txn']=$txn;
            } else {
                $response['result']     = $resultObj->type;
                $response['is_register']= 0;
                $response['message']    = 'New user need to be register';
            }
        } else {
            $response['result']     = $resultObj->type;
            $response['message']    = $resultObj->message;
        }
        
        return response()->json($response);
    }
    
    /**
     * Register User 
     * 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'mobile'    => 'required|digits:10|unique:users,mobile'
        ]);
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        $input = $request->all();
        $input['is_verified']   = 1;     
        $user = User::create($input);        
        if(!empty($user->id)) {
            
            $response['result']     = 'success';                
            $response['message']    = 'Registration successfull';
            $response['data']       = $user;
            $response['token']      = $user->createToken('MyApp')->accessToken;            
            $input['status']         = 'register';
            $sub='Welcome to Gudddeal';
            Mails::sendToMail($sub, $input['email'], $input);
          
        } else {
            $response['result']     = 'error';                
            $response['message']    = 'Something went wrong. Please try again.';
        }
        
        return response()->json($response);
    }

    public function verifyOTPTest(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile'    => 'required|digits:10',
            'otp'       => 'required|digits:4'
        ]);
        
        if($request->otp == 1234) {
            $userModel  = new User();
            $userInfo = $userModel->getUserByMobile($request->mobile);
            if($userInfo) {
                $user       = User::find($userInfo->id);                
                $response['result']     = 'success';                
                $response['is_register']= 1;
                $response['message']    = 'Logged in successfully';
                $response['token']      = $user->createToken('MyApp')->accessToken;
                $response['data']       = User::getUserProfile($user);
            } else {
                $response['result']     = 'success';
                $response['is_register']= 0;
                $response['message']    = 'New user need to be register';
            }
        } else {
            $response['result']     = 'error';
            $response['message']    = 'Error occurred';
        }
        
        return response()->json($response);
    }

    /**
     * User profile api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function profile() {

       
        $user       = Auth::guard('api')->user();
        $userInfo   = User::getUserProfile($user); 
        $txn=User::primeUserTxn($user->id);
        $userInfo['prime_txn']  = $txn;   
        if(!empty($userInfo)) {
            
            $response['result']     = 'success';          
            $response['message']    = '';
            $response['data']       = $userInfo;
            
        } else {
            $response['result']     = 'error';                
            $response['message']    = 'Please login first to view your profile.';
            $response['data']       = array();
        }           
        return response()->json($response);
    }
    
    /**
     * Update user profile api
     * 
     * @return \Illuminate\Http\Response 
     */
    public function update(Request $request) {
        $user   = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'name'  => 'bail|required|min:2',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach ($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            return response()->json($response);
        }
     
        if($user = $user->updateUser($request, $user->id)) {
            $response['result']     = 'success';          
            $response['message']    = 'User profile updated successfully.';
            $response['data']       = $user;
        } else {
            $response['result']     = 'error';                
            $response['message']    = 'User profile not updated. Please try later.';
            $response['data']       = array();
        }
        
        return response()->json($response);
    }
    
    public function primeTxn(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'duration'              => 'bail|required',
            'amount'                => 'required',
            'transaction_details'   => 'required',
        ]);
        $message='';
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach ($errors as $error) {
                $message = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
        }else{
            $user   = Auth::guard('api')->user();
            $insetData  = array(
                'user_id'               => $user->id,
                'duration'              => $request->duration,                
                'amount'                => $request->amount,
                'transaction_details'   => $request->transaction_details,               
            );
            
            $insert = User::primeTxn($insetData);
            if($insert)
            {
                $response['result']     = 'success';
                $response['message']    = 'Your account has been add into prime user.';
            }else{
                $response['result']     = 'error';
                $response['message']    = 'Unable to add into prime user, try again.';  
            }
        }       
        return response()->json($response);        
    }
    
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $user   = Auth::guard('api')->user();
        if(!empty($user)) {
            $user->token()->revoke();
            $response['result']     = 'success';          
            $response['message']    = 'Successfully logged out';
            $response['data']       = array();
        } else {
            $response['result']     = 'error';                
            $response['message']    = 'Please login first.';
            $response['data']       = array();
        }
        
        return response()->json($response);
    }
}
