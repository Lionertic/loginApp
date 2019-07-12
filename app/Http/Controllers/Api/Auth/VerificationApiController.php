<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class VerificationApiController extends Controller
{
    use VerifiesEmails;

    public function verify(Request $request) {
        $userID = $request->id;
        $user = User::findOrFail($userID);
        $user->email_verified_at = Carbon::now();
        $user->save();
        $agent = new Agent();
        $isMobile = $agent->isPhone();
        if($isMobile)
            return response()->json('Email verified!');
        else
            return redirect('/home');
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
