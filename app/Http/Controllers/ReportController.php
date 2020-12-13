<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use CSVReport;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    //

    public function attendance() {
        $title = 'Attendance Report';
        $meta = [];
        $data = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->groupBy('users.name')
            ->select(DB::raw('count(attendances.user_id) as total, users.name'));

        $columns = [
            'user_id' => 'name',
            'total_attendance' => 'total'
        ];

        return CSVReport::of($title, $meta, $data, $columns)
                ->download('testreport.csv');

    }
}
