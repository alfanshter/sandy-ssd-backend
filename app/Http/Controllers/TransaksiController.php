<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransaksiController extends Controller
{

    public function insertcart(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'uid' => 'required',
            'id_barang' => 'required',
            'nama' => ' required',
            'harga' => ' required',
            'harga_total' => ' required',
            'jumlah' => ' required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $check = DB::table('transaksis')->where('uid',$request->uid)->where('id_barang',$request->id_barang)->where('status',"proses")->first();

        if($check==null){
            try {
            
                $inserttransaksi = DB::table('transaksis')->insert([
                    'uid' => $request->uid,
                    'id_barang' => $request->id_barang,
                    'nama' => $request->nama,
                    'harga' => $request->harga,
                    'harga_total' => $request->harga_total,
                    'jumlah' => $request->jumlah,
                    'status' => 'proses'
                ]            );
                $response = [
                    'message' => 'tambah transaksi berhasil',
                    'data' => 1
                ];
    
                return response()->json($response,Response::HTTP_OK);
    
            } catch (QueryException $e) {
                return response()->json(['message' => "Failed", 'data' => $e->errorInfo],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }else{
            $updatecart = DB::table('transaksis')->where('uid',$request->uid)->where('id_barang',$request->id_barang)->where('status','proses')->update([
                'jumlah'=> $request->jumlah,
                'harga_total' => $request->harga_total
            ]);
    
            $response = [
                'message' => 'berhasil update',
                'data' => 2
            ];
                return response()->json($response,Response::HTTP_OK);
        }


    }

    public function gettransaksi(Request $request)
    {
        $gettransaksi = DB::table('transaksis')->where('uid',$request->input('uid'))->where('status',"proses")->get();
        $response = [
            'message' => 'get transaksi',
            'data' => $gettransaksi
        ];

        if($gettransaksi==null){
            $response = [
                'message' => 'get transaksi',
                'data' => $gettransaksi
            ];
            return response()->json($response,Response::HTTP_OK);   
        }
        return response()->json($response,Response::HTTP_OK);

    }

    public function sumcart(Request $request)
    {
        $getsum = DB::table('transaksis')->where('uid',$request->input('uid'))->where('status',"proses")->sum('harga_total');
        $int = (int)$getsum;
        $response = [
            'message' => 'get transaksi',
            'data' => $int
        ];
        return response()->json($response,Response::HTTP_OK);   
    }

    public function hapustransaksi($id)
    {
        $hapuscart = DB::table('transaksis')->where('id',$id)->delete();
        if ($hapuscart > 0) {
            $response = [
                'message' => 'berhasil dihapus',
                'data' => $hapuscart
            ];
            return response()->json($response,Response::HTTP_CREATED);
        }else{
            $response = [
                'message' => 'gagal hapus',
                'data' => $hapuscart
            ];
            return response()->json($response,Response::HTTP_OK);

        }

    }

    public function updatetransaksi(Request $request)
    {
        $updatecart = DB::table('transaksis')->where('uid',$request->uid)->where('id_barang',$request->id_barang)->where('status','proses')->update([
            'jumlah'=> $request->jumlah,
            'harga_total' => $request->harga_total
        ]);

        $response = [
            'message' => 'berhasil update',
            'data' => $updatecart
        ];
            return response()->json($response,Response::HTTP_OK);
    }

    public function hapustransaksiselesai($uid)
    {
        $hapuscart = DB::table('transaksis')->where('uid',$uid)->where('status',"selesai")->delete();
        if ($hapuscart > 0) {
            $response = [
                'message' => 'berhasil dihapus',
                'data' => $hapuscart
            ];
            return response()->json($response,Response::HTTP_CREATED);
        }else{
            $response = [
                'message' => 'gagal hapus',
                'data' => $hapuscart
            ];
            return response()->json($response,Response::HTTP_OK);

        }

    }
   
    public function checkout(Request $request)
    {
        //hapus riwayat order
        $hapusriwayat = DB::table('transaksis')->where('uid',$request->uid)->where('status','selesai')->delete();

        $updatecart = DB::table('transaksis')->where('uid',$request->uid)->update([
            'status'=> "selesai"
        ]);

         $response = [
            'message' => 'status update',
            'data' => $updatecart
        ];
            return response()->json($response,Response::HTTP_OK);
    }

    public function getcheckout(Request $request)
    {
        $gettransaksi = DB::table('transaksis')->where('uid',$request->input('uid'))->where('status',"selesai")->get();
        $response = [
            'message' => 'get transaksi',
            'data' => $gettransaksi
        ];

        if($gettransaksi==null){
            $response = [
                'message' => 'get transaksi',
                'data' => $gettransaksi
            ];
            return response()->json($response,Response::HTTP_OK);   
        }
        return response()->json($response,Response::HTTP_OK);    }

        public function sumcheckout(Request $request)
        {
            $getsum = DB::table('transaksis')->where('uid',$request->input('uid'))->where('status','selesai')->sum('harga_total');
            $int = (int)$getsum;
            $response = [
                'message' => 'get transaksi',
                'data' => $int
            ];
            return response()->json($response,Response::HTTP_OK);   
        }
}
