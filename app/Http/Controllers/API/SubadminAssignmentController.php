<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Support\Facades\DB;  

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubadminAssignmentController extends Controller
{
    //
    public function assign(Request $request)
{
    $request->validate([
        'subadmin_id' => 'required|exists:users,id',
        'classroom_ids' => 'required|array',
        'classroom_ids.*' => 'exists:classrooms,id',
    ]);

    try {
        $admin = $request->user();

        if (!$admin->hasRole('admin')) {
            return response()->json(['message' => 'Only admins can assign subadmins.'], 403);
        }

        $subadmin = User::findOrFail($request->subadmin_id);

        if ($subadmin->school_id !== $admin->school_id) {
            return response()->json(['message' => 'Subadmin does not belong to your school.'], 403);
        }

        foreach ($request->classroom_ids as $classroomId) {
            DB::table('subadmin_classroom_assignments')->updateOrInsert(
                [
                    'school_id' => $admin->school_id,
                    'subadmin_id' => $subadmin->id,
                    'classroom_id' => $classroomId
                ],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        return response()->json(['message' => 'Classrooms assigned to subadmin successfully.']);

    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

public function unassign(Request $request)
{
    $request->validate([
        'subadmin_id' => 'required|exists:users,id',
        'classroom_ids' => 'required|array',
        'classroom_ids.*' => 'exists:classrooms,id',
    ]);

    try {
        $admin = $request->user();

        if (!$admin->hasRole('admin')) {
            return response()->json(['message' => 'Only admins can unassign subadmins.'], 403);
        }

        $subadmin = User::findOrFail($request->subadmin_id);

        if ($subadmin->school_id !== $admin->school_id) {
            return response()->json(['message' => 'Subadmin does not belong to your school.'], 403);
        }

        DB::table('subadmin_classroom_assignments')
            ->where('subadmin_id', $subadmin->id)
            ->whereIn('classroom_id', $request->classroom_ids)
            ->delete();

        return response()->json(['message' => 'Classrooms unassigned from subadmin successfully.']);

    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

public function listAssignedClassrooms(Request $request)
{
    try {
        $user = $request->user();

        if (!$user->hasRole('subadmin')) {
            return response()->json(['message' => 'Only subadmins can view their assigned classrooms.'], 403);
        }

        $classrooms = DB::table('subadmin_classroom_assignments')
            ->join('classrooms', 'subadmin_classroom_assignments.classroom_id', '=', 'classrooms.id')
            ->where('subadmin_classroom_assignments.subadmin_id', $user->id)
            ->select('classrooms.*')
            ->get();

        return response()->json(['classrooms' => $classrooms]);

    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}


}
