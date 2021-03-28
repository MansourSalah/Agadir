<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


use Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(){
        if(!Session::has("lang")){
            Session::put("lang","fr");
        }
    }
   
    public static function forget_all(){
        Session::flush();
    }
    public static function put_s($key,$value){
        Session::put($key,$value);
    }
    public static function get_s($key){
        return Session::get($key);
    }
    public static function forget_s($key){
        Session::forget($key);
    }
    public static function has_s($key){
        return Session::has($key);
    }
    //userC => ['id' => $id ,'role' => $role]
    public static function isAdmin(){
        if(Session::has('userC')){
            $user=Session::get('userC');
            return  $user['role']==1;
        }else
            return false;        
    }
    public static function response($flag,$message_francais,$message_arabic=null){
        if(Session::get('lang')=="fr")
            return response()->json(['flag'=>$flag,'message'=>$message_francais]); 
        else{
            if($message_arabic==null)
                return response()->json(['flag'=>$flag,'message'=>$message_francais]); 
            else
                return response()->json(['flag'=>$flag,'message'=>$message_arabic]);
        }          
    }
    public static function isMax($variable,$valeur){
        if(Session::has($variable)){
            if(Session::get($variable)<$valeur){
                Session::put($variable,Session::get($variable)+1);
                return false;
            }else{
                return true;
            }
        }else{
            Session::put($variable,1);
            return false;
        }
    }
    public static function UserC(){
        if(Session::has('userC')){
            $user=Session::get('userC');
            return User::find($user['id']);
        }else
            return null;
    }
}
