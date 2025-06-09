<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'type' => 'nullable|string|in:primary,secondary,tertiary',
            // 'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            // 'locale' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            // 'postal_code' => 'nullable|string|max:20',
            'registration_number' => 'nullable|string|max:50',
            // 'tax_identification_number' => 'nullable|string|max:50',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create school
        $school = School::create([
            'name' => $request->school_name,
            'email' => $request->admin_email,
            'address' => $request->address,
            'phone' => $request->phone,
            'logo' => $request->logo ? $request->file('logo')->store('logos', 'public') : null,
            'website' => $request->website,
            'type' => $request->type ?? 'primary',
            'currency' => $request->currency ?? 'NGN',
            'country' => $request->country ?? 'Nigeria',
            'state' => $request->state,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->password),
            'type' => 'admin',
            'school_id' => $school->id,
        ]);

        // Assign role
        $admin->assignRole('admin');

        $token = $admin->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'School registered successfully',
            'token' => $token,
            'user' => $admin
        ]);
    }
}

