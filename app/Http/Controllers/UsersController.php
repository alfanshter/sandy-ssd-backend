<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    
    public function index()
    {
        $readuser = User::orderBy('created_at','DESC')->get();
        $response = [
           'message' => 'List akte order by time',
            'data' => $readuser
        ];

        return response()->json($response,Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'uid' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'nohp' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
            'alamat' => ['required'],
            'kota' => ['required']
                ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $aktekelahiran = User::create($request->all());
            $response = [
                'message' => 'register berhasil',
                'data' => $aktekelahiran
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json(['message' => "Failed", 'data' => $e->errorInfo],Response::HTTP_UNPROCESSABLE_ENTITY);

        }



    }

    public function show(Request $request)
    {
        $detailakun = DB::table('users')->where('uid',$request->input('uid'))->first();
        $response = [
            'message' => 'detail akun',
            'data' => $detailakun
        ];

        if($detailakun==null){
            $response = [
                'message' => 'detail akun',
                'data' => 'akun tidak terdaftar'
            ];
            return response()->json($response,Response::HTTP_OK);   
        }
        return response()->json($response,Response::HTTP_OK);   
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'uid' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'nohp' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
            'alamat' => ['required'],
            'kota' => ['required']
                ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            
            $updateakun = DB::table('users')->where('uid',$request->uid)->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'nohp' => $request->nohp,
                'email' => $request->email,
                'password' => $request->password,
                'alamat' => $request->alamat,
                'kota' => $request->kota    
            ]);

            $response = [
                'message' => 'berhasil update',
                'data' => $updateakun
            ];

            return response()->json($response,Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }


    public function destroy(Request $request)
    {
        $deleteakun = DB::table('users')->where('uid',$request->uid)->delete();
        if ($deleteakun > 0) {
            $response = [
                'message' => 'berhasil dihapus',
                'data' => $deleteakun
            ];
            return response()->json($response,Response::HTTP_CREATED);
        }else{
            $response = [
                'message' => 'gagal hapus',
                'data' => $deleteakun
            ];
            return response()->json($response,Response::HTTP_OK);

        }


    }
}
