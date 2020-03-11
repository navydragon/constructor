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
        foreach ($parts as $part)
        {
            $tlp = new TypologyPart;
            $tlp->name = $part["name"];
            $tlp->typology_id = $tl->id;
            $tlp->save();
        }
        $tl->parts = $tl->typology_parts;
        return $tl;
    }

    public function add_dtp(Request $request)
    {
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
        DppTypologyPart::destroy($request);
    }
}
