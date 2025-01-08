<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $message;
    protected $user;

    public function __construct($user, $message)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function handle()
    {
        // Gửi email qua Mail
        Mail::to($this->user->email)->send(new WelcomeEmail($this->message));
    }
}


