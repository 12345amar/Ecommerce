<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'fullname', 'mobile', 'address', 'near_by_landmark', 'pincode', 'city', 'state', 'address_type', 'created_at', 'updated_at'
    ];
    
    public function User()
    {
        return $this->belongsTo('App\User', 'id', 'customer_id');

    }
    
    public function mapAddressToArray($address = array())
    {
        $data=array();       
        foreach($address as $key => $value)
        {
            $data[$key]['id']                   =  $value['id'];
            $data[$key]['fullname']             =  $value->fullname;
            $data[$key]['mobile']               =  $value->mobile;
            $data[$key]['address']              =  $value->address;
            $data[$key]['near_by_landmark']     =  $value->near_by_landmark;
            $data[$key]['pincode']              =  $value->pincode;
            $data[$key]['city']                 =  $value->city;
            $data[$key]['state']                =  $value->state;
            $data[$key]['address_type']         =  $value->address_type;            
        }
        return $data;
    }
}
