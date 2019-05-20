<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExaminerController extends Controller
{
    public function works()
    {
        $dep=DB::table("groups")
            ->select("departmentID")
            ->where("id", Auth::user()->groupID)
            ->first();
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("groups as g", "u.groupID", "=", "g.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->leftJoin("reviews as r1", "w.rev1", "=", "r1.id")
            ->leftJoin("reviews as r2", "w.rev2", "=", "r2.id")
            ->leftJoin("protections as p", "w.id", "=", "p.workID")
            ->select("u.name as studName","w.themeEn", "w.themeUkr", "u2.name as leaderName", "g.name as groupName",
                "w.studentID as studentID","w.leaderID as leaderID", "d.date","w.file", "w.realPages","w.graphicPages",
                "w.rev1 as rev1", "r1.name as r1n", "r1.workplace as r1w", "r1.degree as r1d", "r1.post as r1p",
                "w.rev2 as rev2", "r2.name as r2n", "r2.workplace as r2w", "r2.degree as r2d", "r2.post as r2p", "p.rate",
                "p.id as pID","p.protocol as prot")
            ->where('g.departmentID', $dep->departmentID)
            ->orderBy("g.name")
            ->orderBy("u.name")
            ->get()
            ->transform(function ($item){
                $item->toggle=false;
                $item->edit=false;
                $item->newNote="";
                $item->questions=DB::table("questions as q")
                    ->leftJoin("users as u", "q.examinerID", "=", "u.id")
                    ->select("question","examinerRate", "u.name")
                    ->where("protID", $item->pID)
                    ->get();
                return $item;
            });
        return response()->json(compact("works"));
    }

}
