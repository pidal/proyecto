<?php

namespace pfg\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;

class UserCreateMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
	    $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('layouts.userFirstLogIn')
	        ->with([
		        'email' => $this->user->email,
		        'url' => url('/') . "/createUsers/restablishpass/". $this->user->token,
		        'user' => $this->user
	        ]);;
    }
}
