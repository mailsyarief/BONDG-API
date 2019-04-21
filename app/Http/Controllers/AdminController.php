<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Auth;
use \Session;
use App\bondg;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
    }

    public function showform_bondg()
    {
        return view('admin.input-bondg');
    }

    public function input_bondg(Request $request)
    {
        $bondg = new bondg;
        $bondg->posko = $request->posko;
        $bondg->tgldg = $request->tgldg;
        $bondg->nodg = $request->nodg;
        $bondg->namapel = $request->namapel;
        $bondg->idpel = $request->idpel;
        $bondg->gardu = $request->gardu;
        $bondg->tarif = $request->tarif;
        $bondg->daya = $request->daya;
        $bondg->nohp = $request->nohp;
        $bondg->nometerlama = $request->nometerlama;
        $bondg->status = "Laporan";
        $bondg->save();
        return redirect('/input-bondg')->with('success', 'BON DG Berhasil Ditambah');
    }

    public function status_bondg()
    {
        $bondg = bondg::get();
        $no = 1;
        return view('admin.status-bondg', compact('bondg', 'no'));
    }

    public function detail_bondg(Request $request)
    {
        $id = $request->id;
        $bondg = bondg::find($id);
        return view('admin.detail-bondg', compact('bondg'));
    }

    public function hapus_bondg(Request $request)
    {
        $id = $request->id;
        $bondg = bondg::find($id);
        $bondg->delete();
        return redirect('/bondg')->with('success', 'BON DG Berhasil Dihapus');
    }

    public function edit_bondg(Request $request, $id)
    {
        $bondg = bondg::find($id);
        $bondg->posko = $request->posko;
        $bondg->tgldg = $request->tgldg;
        $bondg->nodg = $request->nodg;
        $bondg->namapel = $request->namapel;
        $bondg->idpel = $request->idpel;
        $bondg->gardu = $request->gardu;
        $bondg->tarif = $request->tarif;
        $bondg->daya = $request->daya;
        $bondg->nohp = $request->nohp;
        $bondg->nometerlama = $request->nometerlama;
        $bondg->save();
        return redirect('/bondg')->with('success', 'BON DG Berhasil Diubah');
    }
}