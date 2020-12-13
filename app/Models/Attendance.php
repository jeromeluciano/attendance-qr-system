<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Section;
class Attendance extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function section() {
        return $this->belongsTo('App\Models\Section');
    }



}
