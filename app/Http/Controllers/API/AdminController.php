<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
            ->update(["rev1"=>null, "rev2"=>null]);
        DB::table("reviews")
            ->where('id', $request->rev1)
            ->delete();
        DB::table("reviews")
            ->where('id', $request->rev2)
            ->delete();
        DB::table("questions")
            ->where('protID', $request->pid)
            ->delete();
        DB::table("protections")
            ->where('id', $request->pid)
            ->delete();
        DB::table("works")
            ->where('id', $request->data)
            ->delete();
        DB::table("notifications")
            ->insert(['userID' => $request->leaderID,
            'text' => "Адмін видалил роботу студента " . $request->studName . ".",
            'date' => DB::raw('current_timestamp')]);
        DB::table("notifications")
            ->insert(['userID' => $request->studID,
            'text' => "Адмін видалил вашу роботу.",
            'date' => DB::raw('current_timestamp')]);
    }

    public function user($name)
    {
        $user = DB::table("users as u")
            ->where("u.email", $name)
            ->first();
        $group = DB::table("groups as g")
            ->select("name", "departmentID" ,"status")
            ->where("id", $user->groupID)
            ->first();
        $department = DB::table('departments as d')
            ->leftJoin("universities as u", "d.universityID", "u.id")
            ->select("d.name as depName", "u.name as unName")
            ->where('d.id', $group->departmentID)
            ->first();
        $avEx = DB::table('users as u')
            ->leftJoin("groups as g", "g.id", "u.groupID")
            ->leftJoin("departments as d", "d.id", "g.departmentID")
            ->select("u.name as exName", "u.id as exID")
            ->where('d.id', $group->departmentID)
            ->whereIn('u.userTypeID',[3,5])
            ->get();
        $requests = DB::table("requests as r")
            ->leftJoin("users as u", "r.studentID", "=", "u.id")
            ->leftJoin("users as u2", "r.leaderID", "=", "u2.id")
            ->select("u.name as studName", "u2.name as leaderName", "r.studentPriority", "r.leaderPriority", "r.visa")
            ->where("studentID", $user->id)
            ->orWhere('leaderID', $user->id)
            ->get();
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("groups as g", "u.groupID", "=", "g.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->leftJoin("reviews as r1", "w.rev1", "=", "r1.id")
            ->leftJoin("reviews as r2", "w.rev2", "=", "r2.id")
            ->leftJoin("protections as p", "w.id", "=", "p.workID")
            ->select("w.id as id", "u.id as studID", "u.name as studName","w.themeEn", "w.themeUkr", "u2.name as leaderName",
                "w.studentID as studentID","w.leaderID as leaderID", "d.date","w.file", "w.realPages","w.graphicPages",
                "w.rev1 as rev1", "r1.name as r1n", "r1.workplace as r1w", "r1.degree as r1d", "r1.post as r1p",
                "w.rev2 as rev2", "r2.name as r2n", "r2.workplace as r2w", "r2.degree as r2d", "r2.post as r2p", "p.rate as rate",
                "p.id as pID","p.protocol as prot", "p.recommendation as pRec", "g.name as gName")
            ->where("w.studentID", $user->id)
            ->orWhere('w.leaderID', $user->id)
            ->get()
            ->transform(function ($item) {
                $item->toggle=false;
                $item->newDate = $item->date;
                $item->addRev = false;
                $item->newRN = '';
                $item->newRP = '';
                $item->newRW = '';
                $item->newRD = '';
                $item->addQuestion=false;
                $item->editDate=false;
                $item->newQuestion='';
                $item->editTotal=false;
                $item->editProt=false;
                $item->newExID=0;
                $item->newExRate=0;
                $item->newTotalRate=0;
                $item->questions=DB::table("questions as q")
                    ->leftJoin("users as u", "q.examinerID", "=", "u.id")
                    ->select("q.id","question","examinerRate", "u.name")
                    ->where("protID", $item->pID)
                    ->get();
                return $item;
            });
        return response()->json(compact('user', 'group','requests', 'works', "department","avEx"));
    }

    public function editDate(Request $request)
    {
        $date = DB::table("dateDef")
            ->select("id", "load")
            ->where("date", $request->data['newDate'])
            ->first();
        if ($date) {
            $dateLoad = DB::table("works")
                ->where("dateID", $date->id)
                ->get()
                ->count();
            if ($date->load > $dateLoad) {
                DB::table("works")
                    ->where('studentID',$request->data['studID'])
                    ->update(['dateID' => $date->id]);
            }
        } else {
            DB::table("works")
                ->where('studentID', $request->data['studID'])
                ->update(['dateID' => null]);
        }
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

    public function createNewQues(Request $request)
    {
        DB::table("questions")
            ->insert(['protID' => $request->pid, 'examinerID' => $request->exID, 'question' => $request->ques,'examinerRate' => $request->rate]);
        $ques=DB::table("questions as q")
            ->leftJoin("users as u", "q.examinerID", "=", "u.id")
            ->select("question","examinerRate", "u.name")
            ->where("protID", $request->pid)
            ->get();
        return response()->json(compact('ques'));
    }


    public function newUsername(Request $request)
    {
        DB::table("protections")
            ->where('id', $request->id)
            ->update(['rate' => $request->data]);
    }

    public function editRate(Request $request)
    {
        DB::table("protections")
            ->where('id', $request->id)
            ->update(['rate' => $request->rate]);
    }

    public function editRec(Request $request)
    {
        DB::table("protections")
            ->where('id', $request->id)
            ->update(['recommendation' => $request->rec]);
    }

    public function delQues(Request $request)
    {
        DB::table("questions")
            ->where('id', $request->id)
            ->delete();
    }
    public function editProt(Request $request)
    {
        DB::table("protections")
            ->where('id', $request->id)
            ->update(['protocol' => $request->prot]);
    }

    public function newEmail(Request $request)
    {
        DB::table("users")
            ->where('id', $request->id)
            ->update(['email' => $request->data]);
    }

    public function makeWorks(Request $request)
    {
        $text="Адмін розпочав розподіл запитів.";
        DB::table("notifications")
            ->insert(['userID' =>"1", 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        $priorities = [2,3,4,5,6];
        $requests = DB::table("requests")
            ->distinct()
            ->orderBy("id", "ASC")
            ->get()
            ->transform(function($item) {
                $item->totalPriority = (int)($item->studentPriority) + (int)($item->leaderPriority);
                return $item;
            });
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
                    $id = DB::table("works")->orderBy("id", "desc")->first();
                    DB::table("protections")
                        ->insert(['workID' => $id->id]);
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
                    $id = DB::table("works")->orderBy("id", "desc")->first();
                    DB::table("protections")
                        ->insert(['workID' => $id->id]);
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
