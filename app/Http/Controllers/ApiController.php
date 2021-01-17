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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Image;
use Illuminate\Support\Facades\Storage;

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

    public function addPengaduan(Request $request)
    {
        $rules = array(
            'id_user' => 'required',
            'judul' => 'required',
            'pengaduan' => 'required',
            'tempat' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return [
                'status' => 404,
                'message' => $validator->errors()->first()
            ];
        }
            $file = $request->file('foto');
            if($request->file('foto')){
                $namaFile=date('YmdHis').$file->getClientOriginalName();
                $normal = Image::make($file)->encode($file->extension());
                Storage::disk('s3')->put('/images/'.$namaFile, (string)$normal, 'public');
                $foto='https://lizartku.s3.us-east-2.amazonaws.com/images/'.$namaFile;
            }

            DB::table('pengaduans')->insert([
                'id_user' => $request->id_user,
                'judul' => $request->judul,
                'pengaduan' => $request->pengaduan,
                'tempat' => $request->tempat,
                'foto' => $foto,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $message = 'Sukses';
            $status = 200;

        return response([
            'message' => $message,
            'status' => $status
        ]);
    }

    public function pengaduan(Request $request)
    {
        $data=DB::table('pengaduans')->where('id_user',$request->id_user)->get();
        return response($data);
    }

    public function galeri(Request $request)
    {
        $data=DB::table('galeris')
        ->where('type',$request->type)
        ->where('bencana',$request->bencana)
        ->get();
        return response($data);
    }

    function pencegahan(Request $request)
    {
        $pencegahan = DB::table('sopbencanas')->where('namasopbencana', 'like', '%' . $request->bencana . '%')->where('namasopbencana', 'like', '%' . $request->time . '%')->get();
        // $list = DB::table('buku')->where('nama', 'like', '%' . $search . '%')->get();

        return response()->json($pencegahan, 200);
    }

    public function kecamatan($bencana)
    {
        $pencegahan = DB::table('menus')
        ->select('kecamatan')
        ->where('namabencana', 'like', '%' . $bencana . '%')
        ->distinct('kecamatan')->get();
        return response()->json($pencegahan, 200);


    }

}
