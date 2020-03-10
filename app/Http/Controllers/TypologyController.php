<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Typology;
use App\TypologyPart;

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
}
