<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;
    
    const GUEST_USER_ID     = 0;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'avatar', 'is_verified', 'prime_member'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /*
     * A user belongs to products(wishlist)
     */
    public function wishlist() {
        return $this->belongsToMany('App\Product', 'wishlists', 'user_id', 'product_id');
    }
    
    /*
     * A user belongs to one product(basket)
     */
    public function basket() {
        return $this->hasOne('App\Cart');
    }

    /*
     * Fetch user by mobile number
     */
    public function getUserByMobile($mobile) {
        $user = User::where('mobile', $mobile)->first();       
        return $user;        
    }
    
    /*
     * Get User Image
     */
    public static function getUserImage($images, $path = 'images/users/') {
        if(is_array($images)) {
            foreach($images as $key=>$image) {                
                if($image && file_exists(public_path($path . $image))) {
                    $imageUrl   = url($path . $image);
                } else {
                    $imageUrl   = url($path . 'default.png');
                }
                return $imageUrl;
            }
        } else {
            if($images && file_exists(public_path($path . $images))) {
                $imageUrl = url($path . $images);
            } else {
                $imageUrl = url($path . 'avatar.png');                
            }
        }
        
        return $imageUrl;
    }
    
    public static function getUserProfile($user = array()) {
        if(empty($user)) {
            $user   = User::getAuthUser();
        }
       
        $data   = array(
            'name'              => $user->name,
            'email'             => $user->email,
            'mobile'            => $user->mobile,
            'prime_member'      => (User::checkUserPrime($user->id) == true)?'1':'0',
            'avatar'            => User::getUserImage($user->avatar)
        );
        
        return $data;
    }
    
    public function createUser($request) {
        $file_name  = '';
        if ($request->hasfile('avatar')) {
            $file       = $request->file('avatar');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $file_name  = time() . '.' . $extension;
            $destinationPath = public_path('/images/users/');
            $file->move($destinationPath, $file_name);
            $request->avatar = $file_name;
        }
        
        $data   = array(
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => bcrypt($request->get('password')),
            'mobile'        => $request->mobile,
            'prime_member'  => $request->prime_member,
            'avatar'        => $file_name
        );
        
        // hash password
        if ($user = User::create($data)) {
            return $user;
        } else {
            return false;
        }
    }
    
    public function updateUser($request, $id) {
        $file_name  = '';
        $user = User::findOrFail($id);
        if ($request->hasfile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $file_name = 'avatar_'.time() . '.' . $extension;
            $destinationPath = public_path('/images/users/');
            $file->move($destinationPath, $file_name);
            if ($user->avatar && file_exists($destinationPath . $user->avatar)) {
                unlink($destinationPath . $user->avatar);
            }            
            $user->avatar   = $file_name;         
        }
        $user->fill($request->except('roles', 'permissions', 'password', 'mobile'));
        // check for password change
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        
        
        if($user->save()) {
            return $user;
        } else {
            return FALSE;
        }
    }
    
    public function deleteUser($id) {
        $user   = User::findOrFail($id);
        $image  = $user->avatar;
        if ($user->delete()) {
            $path   = public_path('images/users/');
            if ($image && file_exists($path . $image)) {
                unlink($path . $image);
            }
            return TRUE;
        }
        
        return FALSE;
    }
    

    public static function primeTxn($txn=array())
    {
        if(!empty($txn))
        {
            $insert = DB::table('prime_transactions')->insert($txn);
            if($insert)
            {
                return true;
            }else{
                return false;
            }
        }else{
           return false; 
        }
    }
    
    public static function checkUserPrime($userId)
    {
        $txn = DB::table('prime_transactions')                   
                    ->where('user_id', $userId) 
                    ->orderBy('id', 'desc')
                    ->first();  
        if($txn)
        {
            $current_time   = Carbon::now();
            $month          = "+".$txn->duration."months";
            $prime_date     = strtotime($month, strtotime($txn->created_at));        
            $current_time   = strtotime($current_time);
            if($prime_date > $current_time)
            {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
       
    }
    
    public static function primeUserTxn($userId)
    {
        $txn = DB::table('prime_transactions')                   
                    ->where('user_id', $userId) 
                    ->orderBy('id', 'desc')
                    ->first();  
        $array_txn=array();
        if($txn)
        {
                $current_time               = Carbon::now();
                $month                      = "+".$txn->duration."months";
                $prime_date                 = strtotime($month, strtotime($txn->created_at));        
                $current_time               = strtotime($current_time);
                $remain_time                = $prime_date-$current_time;
                
                if($prime_date > $current_time)
                {
                    $difference = $prime_date - $current_time;                  
                    $expire = floor($difference / (60*60*24) );
                }else{
                    $difference = $current_time-$prime_date;
                    $expire = -floor($difference / (60*60*24) );
                }
                
                


                $remain_time                = date('Y-m-d',$remain_time);
                $array_txn['duration']      = $txn->duration;
                $array_txn['amount']        = $txn->amount;
                $array_txn['created_at']    = $txn->created_at;
                $array_txn['expiry']        = $expire.' Days';
                
           
        }
        return $array_txn;
    }

    public static function getAuthUser() {
        return  Auth::guard('api')->user() ?? User::GUEST_USER_ID;
    }
    
    public static function getAuthUserId() {
        $user   = User::getAuthUser();
        if($user) {
            return  $user->id;
        }
        
        return User::GUEST_USER_ID;

    }
}
