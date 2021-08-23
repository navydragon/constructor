<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Typology;
use App\TypologyPart;
use App\DppTypologyPart;
use App\IshVersion;

class TypologyController extends Controller
{

    public function get_typologies()
    {
        $tls = Typology::all();
        foreach ($tls as $tl)
        {
            $tl->parts = $tl->typology_parts;
        }
        return $tls;
    }

    public function add_typology(Request $request)
    {
        $tl = new Typology;
        $tl->name = $request->name;
        $tl->save();
        $parts = $request->parts;
        $n = 1;
        foreach ($parts as $part)
        {
            $tlp = new TypologyPart;
            $tlp->name = $part["name"];
            $tlp->typology_id = $tl->id;
            $tlp->position = $n;
            $n++;
            $tlp->save();
        }
        $tl->parts = $tl->typology_parts;
        return $tl;
    }

    public function update_typology(Request $request)
    {
        $tl = Typology::find($request->id);
        $tl->name = $request->name;
        $tl->save();
        $parts = $request->parts;
        $n = 1;
        foreach ($parts as $part)
        {
            $tlp = TypologyPart::find($part["id"]);
            if ($tlp)
            {
                $tlp->name = $part["name"];
                $tlp->position = $n;
                $n++;
                $tlp->save();
            }else{
                $tlp = new TypologyPart;
                $tlp->name = $part["name"];
                $tlp->typology_id = $tl->id;
                $tlp->position = $n;
                $n++;
                $tlp->save();
            } 
        }
        $tl->parts = $tl->typology_parts;
        return $tl;
    }

    public function remove_part(Request $request)
    {
        $tlp = TypologyPart::find($request->id);
        TypologyPart::destroy($request->id);
    }

    public function add_dtp(Request $request)
    {
        //$doc = $request->part;
        $dtp = New DppTypologyPart;
        $dtp->name = $request->dtp_name;
        $dtp->dpp_id = $request->dpp_id;
        $dtp->typology_id = $request->typology_id;
        $dtp->ish_version_id = $request->ish_version_id;
        $iv = IshVersion::find($request->ish_version_id);
        $elems = $iv->typology_parts->count();
        $dtp->position = $elems+1;
        $dtp->save();
        return $dtp;
    }

    public function remove_dtp(Request $request)
    {
        $dtp = DppTypologyPart::find($request->id);
        $nexts = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->where('position','>',$dtp->position)->get();
        foreach ($nexts as $next)
        {
            $next->position = $next->position - 1;
            $next->save();
        }
        foreach ($dtp->get_knowledges as $kn)
        {
            $kn->valid = false;
            $kn->save();
        }
        $dtp->get_knowledges()->detach();
        DppTypologyPart::destroy($request->id);
    }

    public function update_dtp(Request $request)
    {
        $dtp = DppTypologyPart::find($request->id);
        $dtp->name = $request->name;
        $dtp->save();
        return $dtp;
    }

    public function dtp_move_up(Request $request)
    {
        $dtp = DppTypologyPart::find($request->part);
        $previous = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->where('position','=',$dtp->position - 1)->get()->first();
        $dtp->position = $dtp->position - 1;
        $previous->position = $previous->position + 1;
        $dtp->save();
        $previous->save();
        $dtps = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->orderBy('position','asc')->get();
        return $dtps;
    }

    public function dtp_move_down(Request $request)
    {
        $dtp = DppTypologyPart::find($request->part);
        $previous = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->where('position','=',$dtp->position + 1 )->get()->first();
        $dtp->position = $dtp->position + 1 ;
        $previous->position = $previous->position - 1;
        $dtp->save();
        $previous->save();
        $dtps = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->orderBy('position','asc')->get();
        return $dtps;
    }

    public function dtp_remove(Request $request)
    {
        $dtp = DppTypologyPart::find($request->part);
        $below_parts = DppTypologyPart::where('position','>',$dtp->position)->where('ish_version_id','=',$dtp->ish_version_id)->get();
        foreach ($below_parts as $pt)
        {
            $pt->position = $pt->position - 1;
            $pt->save();
        }
        foreach ($dtp->get_knowledges as $kn)
        {
            $kn->valid = false;
            $kn->save();
        }
        $dtp->get_knowledges()->detach();
        DppTypologyPart::destroy($dtp->id);
        $dtps = DppTypologyPart::where('ish_version_id','=',$dtp->ish_version_id)->orderBy('position','asc')->get();
        return $dtps;
    }

    public function make_tp_positions()
    {
        $tls = Typology::all();
        foreach ($tls as $tl)
        {
            $n = 1;
            foreach ($tl->typology_parts as $part)
            {
                $part->position = $n;
                $n++;
                $part->save();
            }
        }
    }

    public function choose_typology(Request $request)
    {
         $typology = Typology::find($request->typology_id);
         $iv = IshVersion::find($request->ish_version_id);
         foreach ($typology->typology_parts as $part)
         {
             $dtp = New DppTypologyPart;
             $dtp->name = $part->name;
             $dtp->dpp_id = $request->dpp_id;
             $dtp->typology_id = $request->typology_id;
             $dtp->ish_version_id = $request->ish_version_id;
             $elems = $iv->typology_parts->count();
             $dtp->position = $elems+1;
             $dtp->save();
             $iv->typology_id = $typology->id;
             $iv->save();
        }
        $res = DppTypologyPart::where('ish_version_id','=',$iv->id)->orderBy('position','asc')->get();
        return $res;
    }



}
