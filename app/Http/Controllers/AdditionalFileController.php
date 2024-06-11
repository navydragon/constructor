<?php

namespace App\Http\Controllers;
use App\ContentVersion;
use App\StructureSection;
use App\Lection;
use App\AdditionalFile;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class AdditionalFileController extends Controller
{
    public function store(ContentVersion $ct, StructureSection $theme, $type, Request $request)
    {
        $path = $request->file('file');
        $title = $request->name;
        $lection = Lection::firstOrNew(['section_id' =>  $theme->id,'type' => $type,'ct_version_id' => $ct->id]);
        $additionalFilesCount = AdditionalFile::where('lection_id',$lection->id)->get()->count();
        $af = new AdditionalFile;
        $af->lection_id = $lection->id;
        $af->position = $additionalFilesCount+1;
        $af->name = $title;
        $af->url = " ";
        $af->save();
        
        $name = $request->file('file')->getClientOriginalName();
        $extension = $request->file('file')->getClientOriginalExtension();
        $path = $request->file('file')->storeAs('additional_files/'.$af->id, $name);
        $af->url = 'app/'.$path;
        $af->save();
        return $af;
    }

    public function download(Lection $content, AdditionalFile $af)
    {
        return response()->download(storage_path($af->url));
    }

    public function destroy(AdditionalFile $af, Request $request)
    {
        //$file = new Filesystem;
        //$file->cleanDirectory('storage/app/additional_files/'.$af->id);
        Storage::deleteDirectory('additional_files/'.$af->id);
        $af_id = $af->id;
        AdditionalFile::destroy($af->id);
        return $af_id;
    }
}
