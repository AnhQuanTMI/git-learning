<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\SendWelcomeEmail;

class MailController extends Controller
{
    public function sendWelcomeEmail(Request $request)
    {
        $user = User::find($request->id);

        // Kiểm tra xem user có tồn tại không
        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }

        $message = 'Welcome to our website!';

        // Đưa job vào hàng đợi
        dispatch(new SendWelcomeEmail($user, $message));

        return response()->json(['message' => 'Email sent successfully!']);
    }
}
