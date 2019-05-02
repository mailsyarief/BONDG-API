<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bondg extends Model
{
    protected $table = 'bondg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'posko', 'nodg', 'namapel', 'idpel', 'nohp', 
        'gardu', 'tarif', 'daya', 'noagenda', 'nometerlama', 'nometerbaru', 'tgldg', 'tglpk', 'tglkirimpetugas',
        'tglterpasang', 'tglremaja', 'tglbatal', 'status', 'alamat', 'keluhan', 'perbaikan', 'id_petugas', 'waktupengerjaan',
    ];

    public function petugas(){
        return $this->belongsTO(user::class, 'id_petugas', 'id');
    }
}
