<?php
namespace App\Http\Controllers;

use App\Library;
use App\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function __construct()
    {
        // 認証機能有効化
        $this->middleware("auth");
    }

    public function index()
    {
        $libraries = Library::all();

        return view("library.index", [
            "libraries" => $libraries,
            "user" => Auth::user()
        ]);
    }

    public function borrowingForm(Request $request)
    {
        $library = Library::find($request->id);

        return view("library.borrow", [
            "library"=>$library,
            "user" => Auth::user()
        ]);
    }

    public function borrow(Request $request)
    {
        $library = Library::find($request->id);
        $library->user_id = Auth::id();
        $library->save();

        $log = new Log();
        $log->user_id = Auth::id();
        $log->library_id = $request->id;
        $log->rent_date = Carbon::now();
        $log->return_due_date = $request->return_due_date;
        $log->return_date = null;
        $log->save();
        
        return redirect("library/index");
    }

    public function returnBook(Request $request)
    {
        $library = Library::find($request->id);
        $library->user_id = 0;
        $library->save();

        $sql = Log::query();
        $sql->where("user_id", Auth::id());
        $sql->where("library_id", $request->id);
        $sql->orderBy("rent_date", "desc");
        $log = $sql->first();
        $log->return_date = Carbon::now();
        $log->save();

        return redirect("library/index");
    }

    public function history()
    {
        $logs = Log::where("user_id", Auth::id())->get();

        return view("library.borrowHistory",[
            "logs" => $logs,
            "user" => Auth::user()
        ]);
    }
}