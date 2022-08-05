<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
	public function show(User $user): UserResource
	{
		return new UserResource($user);
	}

	public function update(User $user, UpdateUserRequest $request): UserResource
	{
		$user->update([
			'username' => $request->username ?? $user->username,
			'email'    => $request->email ?? $user->email,
			'password' => bcrypt($request->password) ?? $user->password,
		]);

		if ($request->hasFile('avatar'))
		{
			File::delete(public_path('images/avatar/custom') . $user->avatar);
			$file = $request->file('avatar');
			$filename = $file->getClientOriginalName();
			$file->move('images/avatar/custom/', $filename);
			$user->avatar = 'images/avatar/custom/' . $filename;
			$user->save();
		}
		return new UserResource($user);
	}
}
