<?php

namespace App\Http\Controllers\Api;
use App\Models\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubjectController extends Controller
{
    //
      public function index(Request $request)
    {
        try {
            $user = $request->user();
            $subjects = Subject::where('school_id', $user->school_id)->with('classroom')->paginate(15);
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching subjects', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'name' => [
                    'required',
                    Rule::unique('subjects')->where(function ($query) use ($user, $request) {
                        return $query->where('school_id', $user->school_id)
                                     ->where('classroom_id', $request->classroom_id);
                    }),
                ],
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            $subject = Subject::create([
                'name' => $request->name,
                'school_id' => $user->school_id,
                'classroom_id' => $request->classroom_id,
            ]);

            return response()->json(['message' => 'Subject created successfully', 'subject' => $subject], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating subject or subject already exist', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $user = $request->user();
            $subject = Subject::where('id', $id)
                              ->where('school_id', $user->school_id)
                              ->with('classroom')
                              ->first();

            if (!$subject) {
                return response()->json(['message' => 'Subject not found'], 404);
            }

            return response()->json($subject);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving subject', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            $subject = Subject::where('id', $id)
                              ->where('school_id', $user->school_id)
                              ->first();

            if (!$subject) {
                return response()->json(['message' => 'Subject not found'], 404);
            }

            $validated = $request->validate([
                'name' => [
                    'nullable',
                    Rule::unique('subjects')->ignore($subject->id)->where(function ($query) use ($user, $request, $subject) {
                        return $query->where('school_id', $user->school_id)
                                     ->where('classroom_id', $request->classroom_id ?? $subject->classroom_id);
                    }),
                ],
                'classroom_id' => 'nullable|exists:classrooms,id',
            ]);

            $subject->update([
                'name' => $validated['name'] ?? $subject->name,
                'classroom_id' => $validated['classroom_id'] ?? $subject->classroom_id,
            ]);

            return response()->json(['message' => 'Subject updated successfully', 'subject' => $subject]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating subject', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $user = $request->user();
            $subject = Subject::where('id', $id)
                              ->where('school_id', $user->school_id)
                              ->first();

            if (!$subject) {
                return response()->json(['message' => 'Subject not found'], 404);
            }

            $subject->delete();

            return response()->json(['message' => 'Subject deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting subject', 'error' => $e->getMessage()], 500);
        }
    }
}
