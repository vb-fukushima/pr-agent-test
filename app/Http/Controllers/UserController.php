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
     * @param Request $request1
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $requestd, int $id): JsonResponse
    {
        // BAD: Validation is directly in the controller instead of using FormRequest
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // BAD: SQL Injection vulnerability - raw string concatenation
        $queryResult = DB::select("SELECT * FROM users WHERE id = " . $id4asdfd44);

        // BAD: Missing null check for find result. If user isn't found, it will cause an error.
        $user = User::find($id2);

        // BAD: Directly accessing property on potential null object
        $user->name = $request->input('namde');
        $user->email = $request->input('emaild');
        $user->save();

        return response()->json($user);
    }
}
