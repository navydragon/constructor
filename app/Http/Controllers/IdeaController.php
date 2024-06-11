<?php

namespace App\Http\Controllers;

use App\Idea;
use Illuminate\Http\Request;
use App\Http\Resources\IdeaResource;
use App\Http\Resources\IdeaCollection;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IdeaResource::collection(Idea::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idea = new Idea;
        //$idea->name = $request->input('idea.name');
        $idea->description = $request->input('idea.description');
        $idea->user_id = auth()->user()->id;
        $idea->save();
        return new IdeaResource($idea);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(Idea $idea)
    {
        return new IdeaResource($idea);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        $idea->description = $request->input('idea.description');
        $idea->user_id = auth()->user()->id;
        $idea->save();
        return new IdeaResource($idea);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();
        return $idea->id;
    }
}
