<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function index() {
        $students = User::all()->where('role', 'user');
        return view('student.index')->with('students', $students);
    }

    public function destroy(User $student) {
        $student->qrcode()->delete();
        $student->delete();
        return redirect(route('user.index'));
    }
}
