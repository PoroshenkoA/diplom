<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderController extends Controller
{
    public function students()
    {
        $requests = DB::table("requests as r")
            ->leftJoin("users as u", "r.studentID", "u.id")
            ->select("u.name", "r.leaderPriority", "r.visa", "r.id as reqID", "u.id as studentID")
            ->where("r.leaderID", Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->editVisa = $item->visa;
                $item->newPrior = $item->leaderPriority;
                return $item;
            });
        return response()->json(compact("requests"));
    }

    public function works()
    {
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->select("w.studentID as studentID","u.name as studName", "w.themeEn", "w.themeUkr", "d.date")
            ->where('w.leaderID', Auth::user()->id)
            ->get()
            ->transform(function ($item){
                $item->editThemeEn=false;
                $item->editThemeUkr=false;
                $item->newThemeEn=$item->themeEn;
                $item->newThemeUkr=$item->themeUkr;
                return $item;
            });
        return response()->json(compact("works"));
    }

    public function getType()
    {
        $type = DB::table("users")
            ->select("userTypeID")
            ->where('id', Auth::user()->id)
            ->first();
        return response()->json(compact("type"));
    }

    public function updateLeaderPriority(Request $request)
    {
        foreach ($request->data as $item) {
            if ($item['visa'] !== $item['editVisa']) {
                if ($item['editVisa'] === 'true') {
                    $text = "Руководитель " . Auth::user()->name . " одобрил запрос студента по имени " . $item['name'] . ".";
                    $leaderLoad = DB::table("works")
                        ->where("leaderID", Auth::user()->id)
                        ->get()
                        ->count();
                    $leaderMaxLoad = DB::table("users as u")
                        ->select("leaderLoad")
                        ->where("u.id", '=', Auth::user()->id)
                        ->first();
                    if ($leaderLoad < (int)$leaderMaxLoad->leaderLoad && $leaderMaxLoad->leaderLoad !== null) {
                        DB::table("works")
                            ->insert(['studentID' => $item['studentID'], 'leaderID' => Auth::user()->id]);
                        DB::table("requests")
                            ->where('studentID', $item['studentID'])
                            ->delete();
                    } else {
                        if ($leaderMaxLoad->leaderLoad !== null)
                            DB::table("requests")
                                ->where('leaderID', Auth::user()->id)
                                ->delete();
                    }
                } else {
                    $text = "Руководитель " . Auth::user()->name . " убрал визу у запроса студента по имени " . $item['name'] . ".";
                }
                DB::table("notifications")
                    ->insert(['userID' => $item['studentID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
            }
            DB::table("requests")
                ->where('id', $item['reqID'],)
                ->update(['leaderPriority' => $item['newPrior'], 'visa' => $item['editVisa']]);
        }
    }
    public function leaderChangeThemeEn(Request $request)
    {
        $text = "Руководитель " . Auth::user()->name . " изменил тему на английском с \"" . $request->data['themeEn'] . "\" на \"".$request->data['newThemeEn']."\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['studentID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['themeEn' => $request->data['newThemeEn']]);
    }

    public function leaderChangeThemeUkr(Request $request)
    {
        $text = "Руководитель " . Auth::user()->name . " изменил тему на украинском с \"" . $request->data['themeUkr'] . "\" на \"".$request->data['newThemeUkr']."\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['studentID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['themeUkr' => $request->data['newThemeUkr']]);
    }
}
