<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'allow_notification' => 'required|boolean'
        ]);

        return tap($user)->update($request->only('name', 'email', 'allow_notification'));
    }

    public function retrieve($id)
    {
        $user = User::where('id', $id)->first();

        return response()->json($user, 201);
    }
}