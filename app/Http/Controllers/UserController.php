<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Update user information.
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // BAD: Validation is directly in the controller instead of using FormRequest
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // BAD: SQL Injection vulnerability - raw string concatenation
        $queryResult = DB::select("SELECT * FROM users WHERE id = " . $id);

        // BAD: Missing null check for find result. If user isn't found, it will cause an error.
        $user = User::find($id);

        // BAD: Directly accessing property on potential null object
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return response()->json($user);
    }

    /** BAD: SQL injection - request input in raw query */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $users = DB::select("SELECT * FROM users WHERE name LIKE '%" . $q . "%'");
        return response()->json($users);
    }
}
