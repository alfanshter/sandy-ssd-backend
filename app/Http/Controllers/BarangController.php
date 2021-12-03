<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File as FacadesFile;

class BarangController extends Controller
{

    public function index()
    {
        $getbarang = Barang::orderBy('created_at','DESC')->get();
        $response = [
           'message' => 'List versi order by time',
            'data' => $getbarang
        ];

        return response()->json($response,Response::HTTP_OK);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'harga' => ' required',
            'keterangan' => ' required',
            'foto' => ' required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //menyimpan data
        $file = $request->file('foto');

        //namafile
        $namafile = $file->getClientOriginalName();
        //ekstensifile
        $ekstensi = $file->getClientOriginalExtension();
        //realpath
        $realpath = $file->getRealPath();
        //ukuran file
        $ukuranfile = $file->getSize();
        // tipe mime
        $file->getMimeType();
        //tujuan upload
        $tujuanupload = 'img';
        //upload file
        try {
            $file->move($tujuanupload,$file->getClientOriginalName());
            $path = $tujuanupload.'/'.$file->getClientOriginalName();

            $register = new Barang();
            $register->foto = $path;
            $register->nama = $request->nama;
            $register->harga = $request->harga;
            $register->keterangan = $request->keterangan;
            $register->save();
            $response = [
                'message' => 'upload sukses',
                'data' => $register
            ];
            return response()->json($response,Response::HTTP_OK);   
        } catch (QueryException $e) {
            return response()->json(['message' => "Failed", 'data' => $e->errorInfo],Response::HTTP_UNPROCESSABLE_ENTITY);
        }


    }

    public function show($id)
    {
        try {
            $detailbarang = Barang::findorFail($id);
            $response = [
                'message' => 'Detail Makanan',
                'data' =>$detailbarang
            ];
    
            return response()->json($response, Response::HTTP_OK);
            exit;
        } catch (QueryException $th) {
            return response()->json(['message' => "Failed", 'data' => $th->errorInfo],Response::HTTP_UNPROCESSABLE_ENTITY);

        }

    }


    public function edit(Request $request)
    {

        //validasi
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'harga' => ' required',
            'keterangan' => ' required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //get data barang
        $foto = Barang::where('id',$request->id)->first();
        if ($foto == null) {
            $response = [
                'message' => 'tidak ada data',
                'data' => 0
            ];
            return response()->json($response,Response::HTTP_CREATED);
        }

        //seleksi jika foto ada
        if ($request->foto!=null) {
            //hapus foto lama
            FacadesFile::delete($foto->foto);
          
            $file = $request->file('foto');
            $file->getMimeType();
            //tujuan upload
            $tujuanupload = 'img';
            //upload file
            try {
                $file->move($tujuanupload,$file->getClientOriginalName());
                $path = $tujuanupload.'/'.$file->getClientOriginalName();

                $updateakun = DB::table('barangs')->where('id',$request->id)->update([
                    'foto'=> $path,
                    'nama' => $request->nama,
                    'harga' => $request->harga,
                    'keterangan' => $request->keterangan
                ]);
    
                $response = [
                    'message' => 'berhasil update',
                    'data' => 1
                ];
                return response()->json($response,Response::HTTP_OK);   
            } catch (QueryException $e) {
                return response()->json(['message' => "Failed", 'data' => $e->errorInfo],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

        }else{
            $updateakun = DB::table('barangs')->where('id',$request->id)->update([
                'foto'=> $foto->foto,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'keterangan' => $request->keterangan
            ]);

            $response = [
                'message' => 'berhasil update',
                'data' => 1
            ];
                return response()->json($response,Response::HTTP_OK); 
        }
        
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //get data barang
        $foto = Barang::where('id',$id)->first();
        //delete foto
        if ($foto == null) {
            $response = [
                'message' => 'tidak ada data',
                'data' => $foto
            ];
            return response()->json($response,Response::HTTP_CREATED);
        }
        FacadesFile::delete($foto->foto);
 
        //delete mysql

        $hapusbarang = Barang::where('id',$id)->delete();

        if ($hapusbarang > 0) {
            $response = [
                'message' => 'berhasil dihapus',
                'data' => $hapusbarang
            ];
            return response()->json($response,Response::HTTP_CREATED);

        }else{
            $response = [
                'message' => 'gagal hapus',
                'data' => $hapusbarang
            ];
            return response()->json($response,Response::HTTP_OK);
        }
    
    }
}
