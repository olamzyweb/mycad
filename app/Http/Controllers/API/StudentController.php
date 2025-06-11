<?php
namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


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
     $student->classrooms()->sync([
    $request->classroom_id => ['school_id' => $student->school_id]
]);

        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Only admins or subadmins can access this route
        if (!$user->hasAnyRole(['admin', 'subadmin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Student::with(['user', 'classrooms'])->where('school_id', $user->school_id);

        //  Filter by class
        if ($request->has('classroom_id')) {
            $query->whereHas('classrooms', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom_id);
            });
        }

        // Filter by name in particular school
        // This will search both first and last names
        // and return students that match either
         if ($request->has('name')) {
        $query->where(function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->name . '%')
              ->orWhere('last_name', 'like', '%' . $request->name . '%');
        });
    }

        $students = $query->paginate(15);

        return response()->json([
            'data' => $students
        ]);
    }
    public function show(Request $request, $id)
{
     $user = $request->user();

        // Only admins or subadmins can access this route
        if (!$user->hasAnyRole(['admin', 'subadmin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

    // Ensure we only fetch a student belonging to the same school as the admin
    $student = Student::with(['user', 'classrooms'])
        ->where('school_id', $user->school_id)
        ->find($id);

    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    return response()->json([
        'student' => $student
    ]);
}



public function update(Request $request, $id)
{
    $user = $request->user();

    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $student = Student::with('user')->findOrFail($id);

    if ($student->school_id !== $user->school_id) {
        return response()->json(['message' => 'Forbidden: Not your school\'s student'], 403);
    }

    try {
        $validated = $request->validate([
            'name'         => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
            'dob'          => 'nullable|date',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'email'        => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation error',
            'errors'  => $e->errors(),
        ], 422);
    }

    if (isset($validated['email'])) {
        $student->user->email = $validated['email'];
        $student->user->save();
    }

    $student->update([
        'name'  => $validated['name'] ?? $student->name,
        'gender'=> $validated['gender'] ?? $student->gender,
        'dob'   => $validated['dob'] ?? $student->dob,
        'email' => $validated['email'] ?? $student->email,
    ]);

    if ($request->filled('classroom_id')) {
        $student->classrooms()->sync([$request->classroom_id]);
    }

    return response()->json([
        'message' => 'Student updated successfully.',
        'student' => $student->load('user', 'classrooms'),
    ]);
}
public function destroy(Request $request, $id)
{
    $user = $request->user();

    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $student = Student::with('user')->findOrFail($id);

    if ($student->school_id !== $user->school_id) {
        return response()->json(['message' => 'Forbidden: Not your schools student'], 403);
    }

    // Delete related user
    $student->user->delete();

    // Delete student
    $student->delete();

    return response()->json(['message' => 'Student deleted successfully.']);
}

}
