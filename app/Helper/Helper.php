<?php

//    function today() {
//        return \Illuminate\Support\Carbon::now('Asia/Manila');
//    }

    function timeArrived($time) {
        return \Illuminate\Support\Carbon::createFromFormat('H:i:s', $time)->format('h:i a');
    }

    function dateAttendance($date){
        return \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $date)->format('M d, Y');
    }
