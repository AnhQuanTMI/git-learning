<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendWelcomeEmail(Request $request)
    {
        $user = User::find($request->id);

        // Gửi email
        Mail::to($user->email)->send(new WelcomeEmail($user));

        return response()->json(['message' => 'Email sent successfully!']);
    }
}
