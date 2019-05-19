<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class NotificationsController extends Controller
{

    public function index()
    {
        $notifications = DB::table("notifications as n")
            ->where("userID", Auth::user()->id)
            ->where("isHidden", false)
            ->orWhere('userID', '1')
            ->orderBy('date', 'DESC')
            ->get()
            ->transform(function ($item, $key) {
                $item->key=$key;
                $item->date = date('Y-m-d H:i:s', strtotime($item->date));
                return $item;
            });
        return response()->json(compact('notifications'));
    }

    public function makeNot(Request $request)
    {
        $text = "Студент " . Auth::user()->name . " просит отменить визу у руководителя " . $request->data['name'] . ".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);

    }

    public function makeAdminNote(Request $request)
    {
        $text = "Админ говорит ";
        DB::table("notifications")
            ->insert(['userID' => "1", 'text' => $text . $request->text, 'date' => DB::raw('current_timestamp')]);

    }

    public function makeExaminerNote(Request $request)
    {
        DB::table("notifications")
            ->insert(['userID' => $request->data['studentID'], 'text' => "Руководитель " . Auth::user()->name ." пишет: ". $request->data['newNote'], 'date' => DB::raw('current_timestamp')]);
        if(Auth::user()->id!=$request->data['leaderID'])
        DB::table("notifications")
            ->insert(['userID' => $request->data['leaderID'], 'text' =>  "Руководитель ". Auth::user()->name ." пишет о работе студента ".$request->data['studName'].": \"".$request->data['newNote']."\"", 'date' => DB::raw('current_timestamp')]);
    }

    public function hideNote(Request $request)
    {
        if($request->data["userID"]==="1")
            return;
        DB::table("notifications")
            ->where('id', $request->data["id"])
            ->update(['isHidden' => true]);
    }

    public function hideAllNotes(Request $request)
    {
        DB::table("notifications")
            ->where('userID', Auth::user()->id)
            ->update(['isHidden' => true]);
    }
}
