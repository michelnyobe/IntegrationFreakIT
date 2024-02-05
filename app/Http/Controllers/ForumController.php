<?php

namespace App\Http\Controllers;
//creation des Models 

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\rubrique;
use App\Models\categorie;
use App\Models\commentaire;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    //
    public function showForum()
    {
        //
        $users= User::all();
        $commentaires= commentaire::all();
        $categories = categorie::all();
        $rubriques = rubrique::all();
        return view('welcome',compact('users','categories','rubriques'));
    }
    public function redirection()
    {
        //
        $role = Auth::user()->roles_id;
        switch ($role) {
            case 1:
                return redirect('dashboard');
            case 2:
                return redirect('dashboard');
            case 3:
                return redirect('/testregister');
            }
    
}
public function userlist()
    {
        //
        
        $users = User::paginate(20);
        return view('userlist',compact('users')) ;
        
    
}
public function userCreationChart()
{
    $userCounts = User::select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, HOUR(created_at) as hour, COUNT(*) as count'))
        ->groupBy('year', 'month', 'day', 'hour')
        ->orderBy('year')
        ->orderBy('month')
        ->orderBy('day')
        ->orderBy('hour')
        ->get();

    $labels = [];
    $data = [];

    foreach ($userCounts as $userCount) {
        $formattedDate = Carbon::create($userCount->year, $userCount->month, $userCount->day, $userCount->hour)->format('Y-m-d H:i:s');
        $labels[] = $formattedDate;
        $data[] = $userCount->count;
    }

    return view('statistique', compact('labels', 'data'));
}


}
