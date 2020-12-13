<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function attend(User $user) {
        $exists = Attendance::where('user_id', $user->id)
                    ->whereDate('created_at', today('Asia/Manila'))
                    ->exists();
        if ($exists) return ['status' => 'failed', 'name' => $user->name];

        Attendance::create([
            'user_id' => $user->id,
            'section_id' => $user->section_id,
            'time_arrived' => Carbon::now('Asia/Manila')->format('H:i:s'),
            'date' => Carbon::today('Asia/Manila')->format('Y-m-d')
        ]);

        return ['status' => 'succesful'];
    }

    public function index() {
       $attendances = Attendance::with(['user', 'section'])->get()->reverse();
       $sections = Section::where('id', '!=', 1)->get();
        return view('attendance.index')
                ->with('attendances', $attendances)
                ->with('sections', $sections);

    }

    public function filter(Request $request) {
        $from = $request->get('from');
        $to = $request->get('to');
        $sectionId = $request->get('section_id');
        $attendances = '';

        if (!empty($from))
            $attendances = Attendance::where('date','>=', $from)
                ->with(['user', 'section'])
                ->get();

        if( !empty($to) )
            $attendances = Attendance::where('date', '<=', $to)
                ->with(['user', 'section'])
                ->get();

        if ($sectionId != 'all')
            $attendances = Attendance::where('section_id', $sectionId)
                ->with(['user', 'section'])
                ->get();

        if(!empty($from) && !empty($to) && $sectionId != 'all') {
            $attendances = Attendance::
                whereBetween('date', [$from, $to])
                ->where('section_id', $sectionId)
                ->with(['user', 'section'])
                ->get();
        }else if(!empty($from) && !empty($to)){
            $attendances = Attendance::whereBetween('date', [$from, $to])
                ->with(['user', 'section'])
                ->get();
        }
        $attendances->map(function($attendance) {
           $attendance->time_arrived = timeArrived($attendance->time_arrived);
           $attendance->date = dateAttendance($attendance->date);
        });
        return $attendances;
    }
}
