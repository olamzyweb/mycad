<?php
namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        // Ensure only admins can access this
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'admission_number' => 'required|string|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a user account for student
        $studentUser = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // default password
            'school_id' => $user->school_id,
            'type' => 'student'
        ]);

        $studentUser->assignRole('student');

        // Create the student record
        $student = Student::create([
            'user_id' => $studentUser->id,
            'school_id' => $user->school_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'admission_number' => $request->admission_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        // Assign to classroom
        $student->classrooms()->sync([$request->classroom_id]);

        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student
        ]);
    }
}
