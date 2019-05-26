<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderController extends Controller
{
    public function students()
    {
        $requests = DB::table("requests as r")
            ->leftJoin("users as u", "r.studentID", "u.id")
            ->leftJoin("groups as g", "u.groupID", "g.id")
            ->select("u.name", "r.leaderPriority", "r.visa", "r.id as reqID", "u.id as studentID", "g.name as groupName")
            ->where("r.leaderID", Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->editVisa = $item->visa;
                $item->newPrior = $item->leaderPriority;
                return $item;
            });
        return response()->json(compact("requests"));
    }

    public function addRev(Request $request)
    {
        DB::table("reviews")
            ->insert(['name' => $request->name, 'workplace' => $request->wp, 'degree' => $request->d, 'post' => $request->p]);
        $rev = DB::table("reviews")->orderBy("id", "desc")->first();
        $rev = DB::table("reviews")->orderBy("id", "desc")->first();
        $id = $rev->id;
        $work = DB::table("works")
            ->select("rev1", "rev2")
            ->where("studentID", $request->studentID)
            ->first();
        if ($work->rev1 === null) {
            DB::table("works")
                ->where('studentID', $request->studentID)
                ->update(['rev1' => $id]);
        } else {
            DB::table("works")
                ->where('studentID', $request->studentID)
                ->update(['rev2' => $id]);
        }
        if (Auth::user()->userTypeID !== 4)
            DB::table("notifications")
                ->insert(['userID' => $request->studentID, 'text' => "Керівник " . Auth::user()->name . " додал нового рецензента.", 'date' => DB::raw('current_timestamp')]);
        return response()->json(compact("id"));
    }

    public function EditGPages(Request $request)
    {
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['graphicPages' => $request->data['graphicPages']]);
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

    public function EditRealPages(Request $request)
    {
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['realPages' => $request->data['realPages']]);
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
                ->where("studentID", $request->studentID)
                ->update(['file' => $docName]);
            $request->file->storeAs('public', $docName);
            DB::table("notifications")
                ->insert(['userID' => $request->studentID, 'text' => "Керівник " . Auth::user()->name . " відновил ваші напрацювання.", 'date' => DB::raw('current_timestamp')]);
            return response()->json(compact("docName"));
        } else {
            $docName = $request->uuid;
            $request->file->storeAs('public', $request->uuid);
            DB::table("notifications")
                ->insert(['userID' => $request->studentID, 'text' => "Керівник " . Auth::user()->name . " відновил ваші напрацювання.", 'date' => DB::raw('current_timestamp')]);
            return response()->json(compact("docName"));
        }
    }

    public function works()
    {
        $works = DB::table("works as w")
            ->leftJoin("users as u", "w.studentID", "=", "u.id")
            ->leftJoin("users as u2", "w.leaderID", "=", "u2.id")
            ->leftJoin("dateDef as d", "w.dateID", "=", "d.id")
            ->leftJoin("reviews as r1", "w.rev1", "=", "r1.id")
            ->leftJoin("reviews as r2", "w.rev2", "=", "r2.id")
            ->leftJoin("protections as p", "w.id", "=", "p.workID")
            ->select("w.studentID as studentID", "u.name as studName", "w.themeEn", "w.themeUkr", "d.date",
                "w.id as id", "w.file", "w.realPages", "graphicPages",
                "w.rev1 as rev1", "r1.name as r1n", "r1.workplace as r1w", "r1.degree as r1d", "r1.post as r1p",
                "w.rev2 as rev2", "r2.name as r2n", "r2.workplace as r2w", "r2.degree as r2d", "r2.post as r2p", "p.id as pID", "p.rate", "p.protocol as prot")
            ->where('w.leaderID', Auth::user()->id)
            ->get()
            ->transform(function ($item) {
                $item->editThemeEn = false;
                $item->toggle = false;
                $item->editThemeUkr = false;
                $item->editFile = false;
                $item->editRealPages = false;
                $item->editPresentationPages = false;
                $item->addRev = false;
                $item->newRN = '';
                $item->newRP = '';
                $item->newRW = '';
                $item->newRD = '';
                $item->newThemeEn = $item->themeEn;
                $item->newThemeUkr = $item->themeUkr;
                $item->questions = DB::table("questions as q")
                    ->leftJoin("users as u", "q.examinerID", "=", "u.id")
                    ->select("question", "examinerRate", "u.name")
                    ->where("protID", $item->pID)
                    ->get();
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
                    $text = "Керівник " . Auth::user()->name . " схвалив запит студента " . $item['name'] . ".";
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
                        $id = DB::table("works")->orderBy("id", "desc")->first();
                        DB::table("protections")
                            ->insert(['workID' => $id->id]);
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
                    $text = "Керівник " . Auth::user()->name . " скасувал візу у запроса студента " . $item['name'] . ".";
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
        $text = "Керівник " . Auth::user()->name . " змінив тему англійською з \"" . $request->data['themeEn'] . "\" на \"" . $request->data['newThemeEn'] . "\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['studentID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['themeEn' => $request->data['newThemeEn']]);
    }

    public function leaderChangeThemeUkr(Request $request)
    {
        $text = "Керівник " . Auth::user()->name . " змінив тему українською з \"" . $request->data['themeUkr'] . "\" на \"" . $request->data['newThemeUkr'] . "\".";
        DB::table("notifications")
            ->insert(['userID' => $request->data['studentID'], 'text' => $text, 'date' => DB::raw('current_timestamp')]);
        DB::table("works")
            ->where('studentID', $request->data['studentID'])
            ->update(['themeUkr' => $request->data['newThemeUkr']]);
    }
}
