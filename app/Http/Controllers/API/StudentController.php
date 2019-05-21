<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function work()
    {
        $work = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->leftJoin("reviews as r1", "w.rev1", "=", "r1.id")
            ->leftJoin("reviews as r2", "w.rev2", "=", "r2.id")
            ->leftJoin("protections as p", "w.id", "=", "p.workID")
            ->select("u.name as studName","u2.id as leaderID", "u2.name as leaderName", "w.themeEn", "w.id as id",
                "w.themeUkr", "d.date as date", "w.file", "w.realPages","graphicPages",
                "w.rev1 as rev1", "r1.name as r1n", "r1.workplace as r1w", "r1.degree as r1d", "r1.post as r1p",
                "w.rev2 as rev2", "r2.name as r2n", "r2.workplace as r2w", "r2.degree as r2d", "r2.post as r2p", "p.id as pID", "p.rate", "p.protocol as prot")
            ->where('w.studentID', Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->newThemeEn = $item->themeEn;
                $item->newThemeUkr = $item->themeUkr;
                $item->newDate = $item->date;
                $item->questions=DB::table("questions as q")
                    ->leftJoin("users as u", "q.examinerID", "=", "u.id")
                    ->select("question","examinerRate", "u.name")
                    ->where("protID", $item->pID)
                    ->get();
                return $item;
            });
        if(isset($work->id)){
            $work=false;
        }
        return response()->json(compact('work'));
    }

    public function leaders()
    {
        $dep = DB::table("groups")
            ->where('id', Auth::user()->groupID)
            ->first();
        $l=DB::table("requests")
            ->select("leaderID")
            ->where("studentID","=", Auth::user()->id)
            ->get();
        $arr = [];
        foreach ($l as $item) {
            $arr[]=$item->leaderID;
        }
        $leaders = DB::table("users as u")
            ->leftJoin("groups as g", "u.groupID", "=", "g.id")
            ->select("u.name", "u.id", "u.leaderLoad")
            ->where("g.departmentID", $dep->departmentID)
            ->whereIn("u.userTypeID", [2,5])
            ->whereNotIn('u.id', $arr)->get()
            ->transform(function ($item) {
                $item->leaderCurLoad = DB::table("works")
                    ->where("leaderID", $item->id)
                    ->get()
                    ->count();
                $item->radio = '';
                return $item;
            });
        return response()->json(compact('leaders'));
    }


    public function requests()
    {
        $requests = DB::table("requests as r")
            ->leftJoin("users as u", "r.leaderID", "u.id")
            ->select("u.name", "r.studentPriority", "r.visa", "r.id as reqID", "r.leaderID")
            ->where("r.studentID", Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->edit = false;
                $item->newPrior = $item->studentPriority;
                return $item;
            });
        return response()->json(compact("requests"));
    }

    public function getDates()
    {
        $data = [];
        $dateMaxLoad = DB::table("dateDef")
            ->get();
        foreach ($dateMaxLoad as $item) {
            $dateLoad = DB::table("works")
                ->where("dateID", $item->id)
                ->get()
                ->count();
            if ($item->load > $dateLoad && $item->load !== null) {
                $data[] = $item->date;
            }
        }
        return response()->json(compact("data"));
    }

    public function createRequest(Request $request)
    {
        foreach ($request->data as $item) {
            DB::table("requests")
                ->insert(['studentID' => Auth::user()->id, 'leaderID' => $item['id'], 'studentPriority' => $item['radio']]);
        }
    }

    public function DelRev(Request $request)
    {
        DB::table("works")
            ->where('rev1', $request->id)
            ->update(['rev1' => null]);
        DB::table("works")
            ->where('rev2', $request->id)
            ->update(['rev2' => null]);
        DB::table("reviews")
            ->where('id', $request->id)
            ->delete();
    }

    public function addRev(Request $request)
    {
        DB::table("reviews")
            ->insert(['name' => $request->name, 'workplace' => $request->wp, 'degree' => $request->d, 'post' => $request->p]);
        $rev=DB::table("reviews")->orderBy("id", "desc")->first();
        $id=$rev->id;
        $work=DB::table("works")
            ->select("rev1", "rev2")
            ->where("studentID",Auth::user()->id)
            ->first();
        if($work->rev1 === null){
            DB::table("works")
                ->where('studentID', Auth::user()->id)
                ->update(['rev1' => $id]);
        } else {
            DB::table("works")
                ->where('studentID', Auth::user()->id)
                ->update(['rev2' => $id]);
        }
        DB::table("notifications")
            ->insert(['userID' => $request->leaderID, 'text' => "Студент ".Auth::user()->name." додал нового рецезента.", 'date' => DB::raw('current_timestamp')]);
        return response()->json(compact("id"));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'file' => 'mimes:docx'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 7,
                'message' => 'Validation error',
            ], 422);
        }
        $fileExt = $request->file->extension();
        if ($request->uuid === "null") {
            $uuid = (string)Str::uuid();
            $docName = $uuid . "." . $fileExt;
            DB::table("works")
                ->where("studentID", Auth::user()->id)
                ->update(['file' => $docName]);
            $request->file->storeAs('public', $docName);
            DB::table("notifications")
                ->insert(['userID' => $request->leaderID, 'text' => "Студент " . Auth::user()->name . " відновил свої напрацювання.", 'date' => DB::raw('current_timestamp')]);
            return response()->json(compact("docName"));
        } else {
            $docName = $request->uuid;
            $request->file->storeAs('public', $request->uuid);
            DB::table("notifications")
                ->insert(['userID' => $request->leaderID, 'text' => "Студент " . Auth::user()->name . " відновил свої напрацювання.", 'date' => DB::raw('current_timestamp')]);
            return response()->json(compact("docName"));
        }
    }

    public function studChangeThemeEn(Request $request)
    {
        $text = "Студент " . Auth::user()->name . " змінив тему англійською з \"" . $request->data['themeEn'] . "\" на \"" . $request->data['newThemeEn'] . "\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', Auth::user()->id)
            ->update(['themeEn' => $request->data['newThemeEn']]);
    }

    public function studEditRealPages(Request $request)
    {
        DB::table("works")
            ->where('studentID', Auth::user()->id)
            ->update(['realPages' => $request->data['realPages']]);
    }

    public function studEditGPages(Request $request)
    {
        DB::table("works")
            ->where('studentID', Auth::user()->id)
            ->update(['graphicPages' => $request->data['graphicPages']]);
    }

    public function studChangeDate(Request $request)
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
                $text = "Студент " . Auth::user()->name . " змінив дату свого захисту з \"" . $request->data['date'] . "\" на \"" . $request->data['newDate'] . "\".";
                DB::table("notifications")
                    ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
                DB::table("works")
                    ->where('studentID', Auth::user()->id)
                    ->update(['dateID' => $date->id]);
            }
        } else {
            $text = "Студент " . Auth::user()->name . " видалил дату свого захисту.";
            DB::table("notifications")
                ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
            DB::table("works")
                ->where('studentID', Auth::user()->id)
                ->update(['dateID' => null]);
        }
    }


    public function studChangeThemeUkr(Request $request)
    {
        $text = "Студент " . Auth::user()->name . " змінив тему українською з \"" . $request->data['themeUkr'] . "\" на \"" . $request->data['newThemeUkr'] . "\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', Auth::user()->id)
            ->update(['themeUkr' => $request->data['newThemeUkr']]);
    }

    public function deleteRequest(Request $request)
    {
        DB::table("requests")
            ->where('id', $request->data)
            ->delete();
    }

    public function editPrior(Request $request)
    {
        DB::table("requests")
            ->where('id', $request->id)
            ->update(['studentPriority' => $request->data]);
    }
}
