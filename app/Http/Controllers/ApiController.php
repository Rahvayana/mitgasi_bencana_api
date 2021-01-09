<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Diet;
use App\Models\History;
use App\Models\Product;
use App\Models\Tip;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{


    public function login(Request $request)
    {
        $user=User::where('username',$request->username)->first();
        if ($user && Hash::check($request->password, $user->password)&&$user->is_user==1) {
            return response()->json([
                'data'=>$user->id,
                'status'=>200,
                'message'=>'Sukses'
            ]);
        }else{
            return response()->json([
                'status'=>500,
                'message'=>'Gagal Login, Cek Email atau Password'
            ]);
        }
    }

    public function register(Request $request)
    {
        $user=User::where('username',$request->username)->first();
        if($user){
            return response()->json([
                'status'=>500,
                'message'=>'Username Sudah Ada',
            ]);
        }
       try{
        $user=new User();
        $user->name=$request->name;
        $user->username=$request->username;
        $user->email=$request->username.'@gmail.com';
        $user->NIK=$request->NIK;
        $user->password=bcrypt($request->password);
        $user->is_user=1;
        $user->save();
        return response()->json([
             'data'=>$user->id,
             'status'=>200,
             'message'=>'Sukses'
         ]);
       }catch(Exception $e){
        return response()->json([
            'status'=>500,
            'message'=>$e->getMessage(),
        ]);
       }
    }
    public function users(Request $request)
    {
        $id=$request->id_user;
        $data=User::find($id);
        return response($data);
    }

}
