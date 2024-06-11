<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nsi;
use App\IshVersion;
use App\Dpp;
use Auth;
class NsiController extends Controller
{
    public function index(IshVersion $iv)
    {    
        return Nsi::where('ish_version_id',$iv->id)->with('type')->orderByDesc('id')->get();
    }

    public function show(IshVersion $iv,$id)
    {    
        return Nsi::findOrFail($id);
    }

    public function store(IshVersion $iv, Request $request)
    {
        $data = $request->elem;
        $nsi = new Nsi;
        $nsi->type_id = $data["type_id"];
        $nsi->name = " ";
        $nsi->nsiDate = $data["nsiDate"];
        $nsi->nsiNumber = $data["nsiNumber"];
        $nsi->nsiEdit = $data["nsiEdit"];
        $nsi->nsiName = $data["nsiName"];
        $nsi->nsiApproveName = $data["nsiApproveName"];
        $nsi->nsiProtocolDate = $data["nsiProtocolDate"];
        $nsi->nsiProtocolNumber = $data["nsiProtocolNumber"];
        $nsi->nsiCode = $data["nsiCode"];
        $nsi->nsiPeriod = $data["nsiPeriod"];
        $nsi->nsiBasis = $data["nsiBasis"];
        $nsi->nsiAuthors = $data["nsiAuthors"];
        $nsi->nsiEditor = $data["nsiEditor"];
        $nsi->nsiCity = $data["nsiCity"];
        $nsi->nsiYear = $data["nsiYear"];
        $nsi->nsiPages = $data["nsiPages"];
        $nsi->nsiLink = $data["nsiLink"];
        $nsi->nsiFullName = $data["nsiFullName"];
        $nsi->nsiMinistry = $data["nsiMinistry"];

        $nsi->ish_version_id = $iv->id;
        $nsi->author_id = Auth::user()->id;
        $nsi->save();
        $nsi->type = $nsi->type;
        return $nsi;
    }

    public function destroy(IshVersion $iv, $id, Request $request)
    {    
        $nsi = Nsi::findOrFail($id);
        $nsi->skills()->detach();
        $nsi->abilities()->detach();
        $nsi->knowledges()->detach();
        $nsi->tasks()->detach();
        Nsi::destroy($id);
        return $id;
    }

    public function update(IshVersion $iv, $id, Request $request)
    {
        $data = $request->elem;
        $nsi = Nsi::findOrFail($id);
        $nsi->name = " ";
        $nsi->nsiDate = $data["nsiDate"];
        $nsi->nsiNumber = $data["nsiNumber"];
        $nsi->nsiEdit = $data["nsiEdit"];
        $nsi->nsiName = $data["nsiName"];
        $nsi->nsiApproveName = $data["nsiApproveName"];
        $nsi->nsiProtocolDate = $data["nsiProtocolDate"];
        $nsi->nsiProtocolNumber = $data["nsiProtocolNumber"];
        $nsi->nsiCode = $data["nsiCode"];
        $nsi->nsiPeriod = $data["nsiPeriod"];
        $nsi->nsiBasis = $data["nsiBasis"];
        $nsi->nsiAuthors = $data["nsiAuthors"];
        $nsi->nsiEditor = $data["nsiEditor"];
        $nsi->nsiCity = $data["nsiCity"];
        $nsi->nsiYear = $data["nsiYear"];
        $nsi->nsiPages = $data["nsiPages"];
        $nsi->nsiLink = $data["nsiLink"];
        $nsi->nsiFullName = $data["nsiFullName"];
        $nsi->nsiMinistry = $data["nsiMinistry"];
        $nsi->save();
        $nsi->type = $nsi->type;
        return $nsi;
    }

    public function move_nsi(Request $request)
    {
        $dpp_from = Dpp::findOrFail($request->from);
        $dpp_to = Dpp::findOrFail($request->to);
        foreach ($dpp_from->ish_version->nsis as $nsi)
        {
            $nsi->ish_version_id = $dpp_to->ish_version->id;
            $nsi->save();
        }

        return response()->json(['status'=>'OK'],200);
    }

}
