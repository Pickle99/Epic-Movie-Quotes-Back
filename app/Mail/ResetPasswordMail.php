<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
	use Queueable, SerializesModels;

	public $passwordReset;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($passwordReset)
	{
		$this->passwordReset = $passwordReset;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('notifications.forgot-password');
	}
}
