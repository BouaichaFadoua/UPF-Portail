<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        
        if (empty($q)) {
            $users = collect();
            $modules = collect();
        } else {
            $users = User::where('name', 'LIKE', "%{$q}%")
                ->orWhere('email', 'LIKE', "%{$q}%")
                ->get();
                
            $modules = Module::where('nom', 'LIKE', "%{$q}%")
                ->orWhere('code', 'LIKE', "%{$q}%")
                ->get();
        }
            
        return view('search.results', compact('users', 'modules', 'q'));
    }
}
