<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Dữ liệu sẽ truyền vào email

    public function __construct($user)
    {
        $this->user = $user; // Gán dữ liệu cho email
    }

    public function build()
    {
        return $this->view('emails.welcome') // Đường dẫn view
                    ->subject('Welcome to Our Platform') // Tiêu đề email
                    ->with([
                        'userName' => $this->user->name, // Biến truyền vào view
                    ]);
    }
}
