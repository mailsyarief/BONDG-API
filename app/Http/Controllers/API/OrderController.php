<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\bondg;
use App\User;
use Carbon\Carbon;
use Validator;
use Storage;
use File;

class OrderController extends Controller
{
    public function login(Request $request)
    {
        $validate = [
            'username'=>'required|string',
            'password'=>'required|min:8'
        ];

        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) 
        {
            // Validation failed
            return response()->json([
              'message' => $validator->messages(),
            ]);
        } 
        else
        {
            $user = User::where('username',$request->username)->first();
            if($user)
            {
                if($user->active == 1)
                {
                    if( password_verify($request->password, $user->password) )
                    {
                        $token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
                        $postArray = ['remember_token' => $token];
                        $login = User::where('username',$request->username)->update($postArray);
                        $user = User::where('username',$request->username)->get();
                        if($login) 
                        {
                            return response()->json([
                                'error' => 0,
                                'message' => $user->toArray(),
                            ]);
                        }
                    }   
                    else
                    {
                        return response()->json([
                            'error' => 1,
                            'message' => array([
                                "id"=> 0,
                                "name" => "-",
                                "email" => "-",
                                "email_verified_at" => null,
                                "remember_token" => "-",
                                "created_at" => "-",
                                "updated_at" => "-",
                                "role" => 0,
                                "active" => 0,
                                "username" => "-"
                            ]),
                        ]);                        
                    } 
                }
                else
                {
                    return response()->json([
                        'error' => 1,
                        'message' => array([
                            "id"=> 0,
                            "name" => "-",
                            "email" => "-",
                            "email_verified_at" => null,
                            "remember_token" => "-",
                            "created_at" => "-",
                            "updated_at" => "-",
                            "role" => 0,
                            "active" => 0,
                            "username" => "-"
                        ]),
                    ]);  
                }                           
            }
            else
            {
                return response()->json([
                    'error' => 1,
                    'message' => array([
                        "id"=> 0,
                        "name" => "-",
                        "email" => "-",
                        "email_verified_at" => null,
                        "remember_token" => "-",
                        "created_at" => "-",
                        "updated_at" => "-",
                        "role" => 0,
                        "active" => 0,
                        "username" => "-"
                    ]),
                ]); 
            } 
        }
    }

    public function logout(Request $request)
    {
        $token = $request->token;
        $user = User::where('remember_token',$token)->first();
        if($user) 
        {
            $postArray = ['remember_token' => null];
            $logout = User::where('id',$user->id)->update($postArray);
            if($logout) {
                return response()->json([
                    'error' => 0,
                    'message' => 'User Logged Out',
                ]);
            }
        } 
        else {
            return response()->json([
                'error' => 1,
                'message' => 'User not found',
            ]);
        }
    }
    
    public function GetOrder(Request $request)
    {   
        $user = User::where('remember_token', $request->token)->where('active', 1)->first();
        if($user == NULL)
        {
            return response()->json(['error' => 1,'message' => 'Token Salah!'], 200);  
        }
        else
        {
            $petugas = User::where('remember_token', $request->token)->where('active', 1)->first();
            $bondg = bondg::select('posko', 'nodg as id_laporan', 'namapel as nama_pelapor', 'nohp as no_hp pelapor',
                'alamat as alamat_pelapor', 'keluhan', 'noagenda as nomor_agenda', 'tgldg as tgl_bln_thn', 'nometerlama as no_meter_lama', 'nometerbaru as no_meter_baru',
                'daya', 'gardu', 'perbaikan', 'tglkirimpetugas as tgl_kirim_petugas' )
                ->where('id_petugas', '=', $petugas->id)
                ->where('status', '=', 'Pengiriman WO')
                ->get();

            return response()->json(array(
                'error' => 0,
                'message' => $bondg->toArray(),
            ), 200);
        }
        
    }

    public function GetDetailOrder (Request $request)
    {

        $user = User::where('remember_token', $request->token)->where('active', 1)->first();
        if($user == NULL)
        {
            return response()->json(['error' => 1,'message' => 'Token Salah!'], 200);  
        }
        else
        {
            $request->validate([
                'id_laporan' => 'required',
            ]);
            $petugas = User::where('remember_token', $request->token)->where('active', 1)->first();        
            $bondg = bondg::select('posko', 'nodg as id_laporan', 'namapel as nama_pelapor', 'nohp as no_hp pelapor',
                'alamat as alamat_pelapor', 'keluhan', 'noagenda as nomor_agenda', 'tgldg as tgl_bln_thn', 'nometerlama as no_meter_lama', 'nometerbaru as no_meter_baru',
                'daya', 'gardu', 'perbaikan', 'tglkirimpetugas as tgl_kirim_petugas' )
                ->where('nodg', '=', $request->id_laporan)
                ->get();
            $idpetugas = bondg::where('nodg', '=', $request->id_laporan)->get();
    
            if( $petugas->id == $idpetugas[0]->id_petugas)
            {
                return response()->json(array(
                    'error' => 0,
                    'message' => $idpetugas->toArray(),
                ), 200);
            }
            else
            {
                return response()->json(array(
                    'error' => 1,
                    'message' => "Unauthorized",
                ), 200);
            }
        }
        
    }

    public function CancelOrder(Request $request)
    {

        $user = User::where('remember_token', $request->token)->where('active', 1)->first();
        if($user == NULL)
        {
            return response()->json(['error' => 1,'message' => 'Token Salah!'], 200);  
        }
        else
        {
            $request->validate([
                'id_laporan' => 'required',
                'alasan' => 'required',
            ]);
    
            $bondg = bondg::find($request->id_laporan);
            if($bondg->cancel_1 == NULL)
            {
                $bondg->cancel_1 = $request->alasan;
                $bondg->save();
                return response()->json(array(
                    'error' => 0,
                    'message' => "Pembatalan berhasil diajukan",
                ), 200);
            }
            else
            {
                $bondg->cancel_2 = $request->alasan;
                $bondg->tglbatal = Carbon::now();
                $bondg->status = "Batal";
                $bondg->waktupengerjaan = Carbon::now()->diffIndays($bondg->tgldg);
                $bondg->save();
                return response()->json(array(
                    'error' => 0,
                    'message' => "Pembatalan berhasil diajukan",
                ), 200);
            }
        }
        
    }
    public function DoOrder(Request $request)
    {
        $user = User::where('remember_token', $request->token)->where('active', 1)->first();
        if($user == NULL)
        {
            return response()->json(['error' => 1,'message' => 'Token Salah!'], 200);  
        }
        else
        {
            $request->validate([
                'id_laporan' => 'required',
                'kwhlama' => 'required',
                'kwhbaru' => 'required',
                'beritaacara' => 'required',
            ]);
            
            //fetch bondg
            $bondg = bondg::find($request->id_laporan);
            
            //upload
            $kwhlama = $request->file('kwhlama');
            $kwhbaru = $request->file('kwhbaru');
            $beritaacara = $request->file('beritaacara');
            //get path for 
            $path1 = $kwhlama->store('uploads');
            $path2 = $kwhbaru->store('uploads');
            $path3 = $beritaacara->store('uploads');
             
            if((Storage::disk('uploads')->put('uploads', $kwhlama)) && (Storage::disk('uploads')->put('uploads', $kwhbaru)) && (Storage::disk('uploads')->put('uploads', $beritaacara)) )
            {
                $bondg->filename_kwhlama = 'files/'.$path1;
                $bondg->filename_kwhbaru = 'files/'.$path2;
                $bondg->filename_ba = 'files/'.$path3;  
                $bondg->status = "Terpasang";
                $bondg->tglterpasang = Carbon::now();
                $bondg->waktupengerjaan = Carbon::now()->diffIndays($bondg->tgldg);
                $bondg->save();
                return response()->json([
                    'error' => 0,
                    'message' => 'Berhasil Upload',
                ]);
            }
            else
            {
                return response()->json([
                    'error' => 1,
                    'message' => 'Laporan tidak bisa terupload!',
                ]);
            }
        }
    }
}
