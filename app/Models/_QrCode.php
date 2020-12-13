<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _QrCode extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'qrcodes';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
