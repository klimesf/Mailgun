<?php


namespace Klimesf\Mailgun;

use Klimesf;
use Mailgun;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author Filip Klimes <filip@filipklimes.cz>
 * @testCase
 */
class MailgunMailerSendTest extends Tester\TestCase
{

	/**
	 * @param callable $prepareMail
	 * @dataProvider provideTestServicesData
	 */
	public function testSend(\Closure $prepareMail)
	{
		list($mail, $postData, $postFiles) = $prepareMail();

		$mock = new MailgunMock();
		$mailer = new MailgunMailer($mock, 'domain');
		$mailer->send($mail);

		Assert::same('domain', $mock->domain);
		Assert::equal($postData, $mock->postData);
		Assert::same($postFiles, $mock->postFiles);
	}


	protected function provideTestServicesData()
	{
		return [
			[
				function () {
					$mail = new Nette\Mail\Message();
					$mail->setFrom('mailgun@example.com');
					$mail->addTo('mailgun@example.com');
					$mail->setSubject('Hello from mailgun!');
					return [
						$mail,
						[
							'from'    => 'mailgun@example.com',
							'to'      => 'mailgun@example.com',
							'subject' => 'Hello from mailgun!'
						],
						[]
					];
				}
			],
			[
				function () {
					$mail = new Nette\Mail\Message();
					$mail->setFrom('mailgun@example.com');
					$mail->addTo('mailgun@example.com');
					$mail->addTo('mailgun2@example.com');
					$mail->addBcc('mailgun3@example.com');
					$mail->addCc('mailgun4@example.com');
					$mail->setSubject('Hello from mailgun!');
					return [
						$mail,
						[
							'from'    => 'mailgun@example.com',
							'to'      => 'mailgun@example.com, mailgun2@example.com',
							'cc'      => 'mailgun4@example.com',
							'bcc'     => 'mailgun3@example.com',
							'subject' => 'Hello from mailgun!'
						],
						[]
					];
				}
			],
			[
				function () {
					$mail = new Nette\Mail\Message();
					$mail->setFrom('mailgun@example.com');
					$mail->addTo('mailgun@example.com');
					$mail->setSubject('Hello from mailgun!');
					$mail->setBody('body');
					$mail->setHtmlBody('<body>');
					return [
						$mail,
						[
							'from'    => 'mailgun@example.com',
							'to'      => 'mailgun@example.com',
							'subject' => 'Hello from mailgun!',
							'text'    => 'body',
							'html'    => '<body>'
						],
						[]
					];
				}
			],
		];
	}

}


class MailgunMock extends Mailgun\Mailgun
{

	public $domain;

	public $postData;

	public $postFiles;

	public function sendMessage($workingDomain, $postData, $postFiles = array())
	{
		$this->domain = $workingDomain;
		$this->postData = $postData;
		$this->postFiles = $postFiles;
	}

}

run(new MailgunMailerSendTest());
