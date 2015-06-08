<?php


namespace Klimesf\Mailgun;

use Mailgun\Mailgun;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

/**
 * @package   Klimesf\Mailgun
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class MailgunMailer implements IMailer
{

	/**
	 * @var Mailgun
	 */
	private $mailgun;

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @param Mailgun $mailgun
	 * @param string  $domain
	 */
	public function __construct(Mailgun $mailgun, $domain)
	{
		$this->mailgun = $mailgun;
		$this->domain = $domain;
	}

	/**
	 * Sends email.
	 * @param Message $mail
	 * @return void
	 */
	public function send(Message $mail)
	{
		$postData = [
			'from'    => $mail->getHeader('Return-Path') ?: key($mail->getHeader('From')),
			'to'      => $this->getCommaSeparatedEmails((array) $mail->getHeader('To')),
			'cc'      => $this->getCommaSeparatedEmails((array) $mail->getHeader('Cc')),
			'bcc'     => $this->getCommaSeparatedEmails((array) $mail->getHeader('Bcc')),
			'subject' => $mail->getSubject(),
			'text'    => $mail->getBody(),
			'html'    => $mail->getHtmlBody(),
			// TODO missing Attachment for email
			//'atachment' => $cmail->getAtachment()
			// TODO missing inline atachment
			// 'inline' => []
		];

		$this->mailgun->sendMessage($this->domain, array_filter($postData));
	}

	/**
	 * Return comma separated emails
	 * @param array $emails
	 * @return string
	 */
	private function getCommaSeparatedEmails($emails)
	{
		return implode(
			', ', array_map(
				function ($name, $email) {
					return $name ? $name . ' <' . $email . '>' : $email;
				}, $emails, array_keys($emails)
			)
		);
	}
}
