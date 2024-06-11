<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AnnouncementResource;
use App\Announcement;
use Auth;
use Carbon\Carbon;
class AnnouncementController extends Controller
{
    public function index()
    {
        return AnnouncementResource::collection(Announcement::orderBy('is_sticked','desc','date','desc')->get());
    }

    public function show(Announcement $announcement)
    {
        return new AnnouncementResource($designer);
    }

    public function store(Request $request)
    {
        $ann = new Announcement;
        $ann->text = $request->input('announcement.text');
        $ann->user_id = Auth::user()->id;
        $ann->date = Carbon::now();
        $ann->is_hidden = 0;
        $ann->is_sticked = 0;
        
        $ann->save();
        return new AnnouncementResource($ann);
    }

    public function update(Announcement $announcement,Request $request)
    {
        $announcement->text = $request->input('announcement.text');
        //$announcement->date = $request->input('announcement.date');
        $announcement->is_hidden = $request->input('announcement.isHidden');;
        $announcement->is_sticked = $request->input('announcement.isSticked');;
        $announcement->save();
        return new AnnouncementResource($announcement);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return $announcement->id;
    }
}
