<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function work()
    {
        $work = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->select("u2.id as leaderID", "u2.name as leaderName", "w.themeEn", "w.themeUkr", "d.date as date")
            ->where('w.studentID', Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->newThemeEn = $item->themeEn;
                $item->newThemeUkr = $item->themeUkr;
                $item->newDate = $item->date;
                return $item;
            });
        if (!isset($work->leaderID)){
            $work = false;
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
                $item->newPrior = 1;
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

    public function studChangeThemeEn(Request $request)
    {
        $text = "Студент " . Auth::user()->name . " изменил тему на английском с \"" . $request->data['themeEn'] . "\" на \"" . $request->data['newThemeEn'] . "\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', Auth::user()->id)
            ->update(['themeEn' => $request->data['newThemeEn']]);
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
                $text = "Студент " . Auth::user()->name . " изменил дату своей защиты с \"" . $request->data['date'] . "\" на \"" . $request->data['newDate'] . "\".";
                DB::table("notifications")
                    ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
                DB::table("works")
                    ->where('studentID', Auth::user()->id)
                    ->update(['dateID' => $date->id]);
            }
        } else {
            $text = "Студент " . Auth::user()->name . " удалил дату своей защиты.";
            DB::table("notifications")
                ->insert(['userID' => $request->data['leaderID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
            DB::table("works")
                ->where('studentID', Auth::user()->id)
                ->update(['dateID' => null]);
        }
    }


    public function studChangeThemeUkr(Request $request)
    {
        $text = "Студент " . Auth::user()->name . " изменил тему на украинском с \"" . $request->data['themeUkr'] . "\" на \"" . $request->data['newThemeUkr'] . "\".";
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
