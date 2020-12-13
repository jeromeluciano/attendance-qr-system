<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
class SearchController extends Controller
{
    public function find(Request $request) {
        $q = $request->get('query');

        $students = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->with('section')
            ->get();

        $filtered = $students->filter(function($student) {
            return $student->role == 'student';
        });
//
        foreach($filtered as $item) {
            $item['_token'] = csrf_token();
        }
        return ($filtered);
    }
}
