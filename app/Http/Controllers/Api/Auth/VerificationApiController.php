<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationApiController extends Controller
{
    use VerifiesEmails;

    public function verify(Request $request) {
        $userID = $request['id'];
        $user = User::findOrFail($userID);
        $date = Carbon::now();
        $user->email_verified_at = $date;
        $user->save();
        return response()->json('Email verified!');
    }
    /**
     * Resend the email verification notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);

        }
        $request->user()->sendEmailVerificationNotification();

        return response()->json('The notification has been resubmitted');
    }
}
