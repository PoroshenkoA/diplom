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
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->select("u.name as studName","w.themeEn", "w.themeUkr", "u2.name as leaderName","w.studentID as studentID","w.leaderID as leaderID", "d.date")
            ->where('u.departmentID', Auth::user()->departmentID)
            ->get()
            ->transform(function ($item){
                $item->edit=false;
                $item->newNote="";
                return $item;
            });
        return response()->json(compact("works"));
    }

}
