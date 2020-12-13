<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Role;
use App\Models\Section;
use App\Models\Attendance;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'section_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    relationship
    public function section() {
        return $this->belongsTo(Section::class);
    }

    public function qrcode() {
        return $this->hasOne(_QrCode::class, 'user_id');
    }

    public function attendance() {
        return $this->belongsTo(Attendance::class, 'user_id');
    }

    public function generateQrCode() {
        $filename = $this->id.'.'.$this->name.'.svg';
        QrCode::generate($this->urlPath(), public_path('qrcodes/'.$filename));
        return $filename;
    }

    public function urlPath() {
        return route('attendance.attend', ['user' => $this]);
    }

    public function qrCodePath() {
        return asset('qrcodes/'.$this->id.'.'.$this->name.'.svg');
    }

    public function isAdmin() {
        return $this->role == 'admin';
    }

}
