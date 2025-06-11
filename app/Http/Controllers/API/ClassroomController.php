<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    //
    public function index(Request $request)
{
    $user = $request->user();

    // Only admins or subadmins can access
    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $classrooms = Classroom::where('school_id', $user->school_id)->with('students')->paginate(15);

    return response()->json([
        'message' => 'Classrooms fetched successfully.',
        'data' => $classrooms
    ]);
}

public function store(Request $request)
{
    $user = $request->user();
    if (! $user->hasRole('admin')) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    try {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('classrooms')->where(fn($q) => 
                    $q->where('school_id', $user->school_id)
                ),
            ],
        ]);
    } catch (ValidationException $e) {
        // return only the “already exists” error if you like:
        return response()->json([
            'message' => 'Could not create classroom.',
            'errors'  => $e->errors(),
        ], 422);
    }

    $classroom = Classroom::create([
        'name'      => $validated['name'],
        'school_id' => $user->school_id,
    ]);

    return response()->json([
        'message'   => 'Classroom created successfully.',
        'classroom' => $classroom,
    ], 201);
}

public function show(Request $request, $id)
{
    $user = $request->user();

    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $classroom = Classroom::with('students')->where('school_id', $user->school_id)->find($id);

    if (!$classroom) {
        return response()->json(['message' => 'Classroom not found.'], 404);
    }

    return response()->json([
        'message' => 'Classroom details retrieved successfully.',
        'data' => $classroom
    ]);
}

public function update(Request $request, $id)
{
    $user = $request->user();

    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $classroom = Classroom::where('school_id', $user->school_id)->find($id);

    if (!$classroom) {
        return response()->json(['message' => 'Classroom not found.'], 404);
    }

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id . ',id,school_id,' . $user->school_id,
        ]);

        $classroom->update([
            'name' => $validated['name']
        ]);

        return response()->json([
            'message' => 'Classroom updated successfully.',
            'data' => $classroom
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (QueryException $e) {
        return response()->json([
            'message' => 'Database error',
            'error' => $e->getMessage()
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function destroy(Request $request, $id)
{
    $user = $request->user();

    if (!$user->hasAnyRole(['admin', 'subadmin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $classroom = Classroom::where('school_id', $user->school_id)->find($id);

    if (!$classroom) {
        return response()->json(['message' => 'Classroom not found.'], 404);
    }

    // Optional: detach students
    $classroom->students()->detach();

    $classroom->delete();

    return response()->json([
        'message' => 'Classroom deleted successfully.'
    ]);
}

}
