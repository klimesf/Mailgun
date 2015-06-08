<?php


namespace Klimesf\Mailgun\DI;

use Klimesf;
use Mailgun;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Filip Klimes <filip@filipklimes.cz>
 * @testCase
 */
class MailgunExtensionTest extends Tester\TestCase
{

	/**
	 * @param string $section
	 * @return Nette\DI\Container
	 */
	protected function createContainer($section)
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/../../config.neon', $section);
		return $config->createContainer();
	}


	/**
	 * @dataProvider provideTestIncorrectSettingsData
	 */
	public function testIncorrectSettings($section)
	{
		Assert::exception(function () use ($section) {
			$this->createContainer($section);
		}, '\UnexpectedValueException', 'Please configure the Mailgun extensions using the section \'mailgun:\' in your config file.');
	}


	/**
	 * @return array
	 */
	protected function provideTestIncorrectSettingsData()
	{
		return [
			['incorrect1'],
			['incorrect2'],
		];
	}


	public function testServices()
	{
		$dic = $this->createContainer('correct');
		Assert::true($dic->getService('mailgun.mailgun') instanceof Mailgun\Mailgun);
		Assert::true($dic->getService('mailgun.mailer') instanceof Klimesf\Mailgun\MailgunMailer);
	}

}

run(new MailgunExtensionTest());
