<?php
/**
 * Created by PhpStorm.
 * User: reeve
 * Date: 4/6/2017
 * Time: 3:36 PM
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class MailerService
{

	private $token_service;

	public function __construct(TokenService $token_service)
	{
		$this->token_service = $token_service;
	}

	public function send_mail($sender_email, $sender_name, $recipient, $subject, $view, $data = [])
	{

		Mail::send($view, $data, function($message) use($sender_email, $sender_name, $recipient, $subject){

			$message->from($sender_email, $sender_name);
			$message->to($recipient->email)->subject($subject);

		});
	}

	public function send_activation_email($recipient, $name, $view = 'mail/account_activation', $subject = 'Taktyx Registration')
	{
		// activation keys and digest
		$activation_keys = $this->token_service->create();
		$recipient->activation_digest = $activation_keys['key_encoded'];
		$recipient->save();

		// send activation email
		$this->send_mail('noreply@taktyx.com', $subject,
			$recipient, 'Account Activation', $view,
			['key' => $activation_keys['key'], 'id' => $recipient->id,
			 'name' => $name]);
	}

	public function send_reset_email($mailable, $subject, $recipient_name, $view = 'mail/password_reset', $expiration = 10)
	{
		// send email
		$token = $this->token_service->create();
		$mailable->reset_digest = $token['key_encoded'];
		$mailable->reset_digest_expire = Carbon::now()->addMinutes($expiration);
		$mailable->save();

		$this->send_mail('passwordresets@taktyx.com', 'Taktyx Admin', $mailable,
			$subject, $view, ['key' => $token['key'], 'name' => $recipient_name, 'id' => $mailable->id]);
	}

}