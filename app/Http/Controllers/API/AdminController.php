<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function userNotes($name)
    {
        $notifications = DB::table("notifications as n")
            ->leftJoin("users as u", "n.userID", "u.id")
            ->select("n.date", "n.text")
            ->where("u.email", $name)
            ->orWhere('n.userID', '1')
            ->orderBy('n.date', 'DESC')
            ->get()
            ->transform(function ($item) {
                $item->date = date('Y-m-d H:i:s', strtotime($item->date));
                return $item;
            });
        return response()->json(compact('notifications'));
    }

    public function deleteWork(Request $request)
    {
        DB::table("works")
            ->where('id', $request->data)
            ->delete();
        DB::table("notifications")
            ->insert(['userID' => $request->leaderID,
            'text' => "Админ удалил работы студента " . $request->studNam . ".",
            'date' => DB::raw('current_timestamp')]);
        DB::table("notifications")
            ->insert(['userID' => $request->studID,
            'text' => "Админ удалил вашу работу.",
            'date' => DB::raw('current_timestamp')]);
    }

    public function user($name)
    {
        $user = DB::table("users as u")
            ->where("u.email", $name)
            ->first();
        $department = DB::table('departments as d')
            ->leftJoin("universities as u", "d.universityID", "u.id")
            ->select("d.name as depName", "u.name as unName")
            ->where('d.id', $user->departmentID)
            ->first();
        $requests = DB::table("requests as r")
            ->leftJoin("users as u", "r.studentID", "=", "u.id")
            ->leftJoin("users as u2", "r.leaderID", "=", "u2.id")
            ->select("u.name as studName", "u2.name as leaderName", "r.studentPriority", "r.leaderPriority", "r.visa")
            ->where("studentID", $user->id)
            ->orWhere('leaderID', $user->id)
            ->get();
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->select("u.name as studName","w.id as id", "u2.name as leaderName", "w.themeEn", "w.themeUkr", "w.studentID as studID", "w.leaderID as leaderID", "d.date")
            ->where("w.studentID", $user->id)
            ->orWhere('w.leaderID', $user->id)
            ->get();
        return response()->json(compact('user', 'requests', 'works', "department"));
    }

    public function dateNotes($date)
    {
        $newDate = date('Y-m-d', strtotime($date));
        $notifications = DB::table("notifications as n")
            ->select("n.date", "n.text")
            ->whereDate("n.date", '=', $newDate)
            ->orderBy('n.date', 'DESC')
            ->get()
            ->transform(function ($item) {
                $item->date = date('Y-m-d H:i:s', strtotime($item->date));
                return $item;
            });
        return response()->json(compact('notifications'));
    }

    public function newUsername(Request $request)
    {
        DB::table("users")
            ->where('id', $request->id)
            ->update(['name' => $request->data]);
    }

    public function newEmail(Request $request)
    {
        DB::table("users")
            ->where('id', $request->id)
            ->update(['email' => $request->data]);
    }

    public function makeWorks(Request $request)
    {
        $text="Админ запустил распределение заявок.";
        DB::table("notifications")
            ->insert(['userID' =>"1", 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        $requests = DB::table("requests")
            ->distinct()
            ->orderBy("id", "ASC")
            ->get()
            ->transform(function($item) {
                $item->totalPriority = (int)($item->studentPriority) * 2 + (int)($item->leaderPriority);
                return $item;
            });
        $priorities = [2,3,4,5,6];
        foreach ($priorities as $prior) {
            foreach($requests as $req){
                $leaderLoad=DB::table("works")
                    ->where("leaderID",$req->leaderID)
                    ->get()
                    ->count();
                $leaderMaxLoad=DB::table("users as u")
                    ->select("leaderLoad")
                    ->where("u.id", '=', $req->leaderID)
                    ->first();
                if($req->totalPriority === $prior && $req->visa === true && $leaderLoad<(int)$leaderMaxLoad->leaderLoad){
                    DB::table("works")
                        ->insert(['studentID' => $req->studentID, 'leaderID' => $req->leaderID]);
                    DB::table("requests")
                        ->where('studentID', $req->studentID)
                        ->delete();
                    $requests = DB::table("requests")
                        ->distinct()
                        ->get()
                        ->transform(function($item) {
                            $item->totalPriority = (int)($item->studentPriority) * 2 + (int)($item->leaderPriority);
                            return $item;
                        });
                }
                if($leaderLoad>=(int)$leaderMaxLoad->leaderLoad) {
                    DB::table("requests")
                        ->where('leaderID', $req->leaderID)
                        ->delete();
                }
            }
            foreach($requests as $req){
                $leaderLoad=DB::table("works")
                    ->where("leaderID",$req->leaderID)
                    ->get()
                    ->count();
                $leaderMaxLoad=DB::table("users as u")
                    ->select("leaderLoad")
                    ->where("u.id", '=', $req->leaderID)
                    ->first();
                if($req->totalPriority === $prior && $req->visa === false  && $leaderLoad<(int)$leaderMaxLoad->leaderLoad){
                    DB::table("works")
                        ->insert(['studentID' => $req->studentID, 'leaderID' => $req->leaderID]);
                    DB::table("requests")
                        ->where('studentID', $req->studentID)
                        ->delete();
                    $requests = DB::table("requests")
                        ->distinct()
                        ->get()
                        ->transform(function($item) {
                            $item->totalPriority = (int)($item->studentPriority) + (int)($item->leaderPriority);
                            return $item;
                        });
                }
                if($leaderLoad>=(int)$leaderMaxLoad->leaderLoad) {
                    DB::table("requests")
                        ->where('leaderID', $req->leaderID)
                        ->delete();
                }
            }
        }

    }
}
