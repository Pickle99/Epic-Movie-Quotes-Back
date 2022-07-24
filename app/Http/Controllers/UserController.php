<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
	public function show(User $user)
	{
		return new UserResource($user);
	}

	public function update(User $user, UpdateUserRequest $request)
	{
		$user->username = $request->username;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		if ($request->hasFile('avatar'))
		{
			File::delete(public_path('images/avatar/custom') . $user->avatar);
			$file = $request->file('avatar');
			$filename = $file->getClientOriginalName();
			$file->move('images/avatar/custom/', $filename);
			$user->avatar = 'images/avatar/custom/' . $filename;
		}
		$user->update();
		return new UserResource($user);
	}
}
