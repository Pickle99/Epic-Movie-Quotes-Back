<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
	public function resetPassword(ForgotPasswordRequest $request): JsonResponse
	{
		$token = Str::random(60);
		$user = User::where('email', $request->email)->first();
		$passwordReset = PasswordReset::create([
			'user_id'    => $user->id,
			'email'      => $request->email,
			'token'      => $token,
		]);
		Mail::to($request->email)->send(new ResetPasswordMail($passwordReset));
		return response()->json('Password reset email successfully sent to your email', 200);
	}

	public function updatePassword(PasswordResetRequest $request, string $token): JsonResponse
	{
		$passwordReset = PasswordReset::where('token', $token)->first();
		$user = User::find($passwordReset->user_id);
		$user->update([
			'password' => bcrypt($request->password),
		]);
		$passwordReset->delete();
		return response()->json('Password changed successfully', 200);
	}
}
