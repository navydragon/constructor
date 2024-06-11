<?php

namespace App\Http\Controllers;

use App\Designer;
use Illuminate\Http\Request;
use App\Dpp;
use App\Http\Resources\DesignerResource;
use App\Http\Resources\DesignerCollection;
use App\Http\Requests\DesignerRequest;
class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Dpp $dpp)
    {
        $designers = $dpp->designers;
        return new DesignerCollection($designers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Dpp $dpp,DesignerRequest $request)
    {
        $designer = new Designer;
        $designer->lastname = $request->input('performer.lastname');
        $designer->firstname = $request->input('performer.firstname');
        $designer->middlename = $request->input('performer.middlename');
        $designer->title = $request->input('performer.title');
        $designer->degree = $request->input('performer.degree');
        $designer->degree_short = $request->input('performer.degreeShort');
        $designer->task = $request->input('performer.task');
        $designer->dpp_id = $dpp->id;
        $designer->position = $dpp->designers()->count()+1;
        $designer->save();
        return new DesignerResource($designer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function show(Dpp $dpp,Designer $designer)
    {
        return new DesignerResource($designer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function update(DesignerRequest $request, Dpp $dpp, Designer $designer)
    {
        $designer->lastname = $request->input('performer.lastname');
        $designer->firstname = $request->input('performer.firstname');
        $designer->middlename = $request->input('performer.middlename');
        $designer->title = $request->input('performer.title');
        $designer->degree = $request->input('performer.degree');
        $designer->degree_short = $request->input('performer.degreeShort');
        $designer->task = $request->input('performer.task');
        $designer->save();
        return new DesignerResource($designer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dpp $dpp,Designer $designer)
    {
        $designer->changePositionBelow();
        $designer->delete();
        return $designer->id;
    }

    public function reorder(Dpp $dpp,Request $request)
    {
        $position = 1;
        $ids = $request->ids;
        
        foreach ($ids as $id)
        {
            $designer = Designer::findOrFail($id);
            $designer->position = $position;
            $designer->save();
            $position++;
        }
        return new DesignerCollection($dpp->designers);
    }
}
