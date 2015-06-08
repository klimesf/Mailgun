<?php


namespace Klimesf\Mailgun\DI;

use Nette\DI\CompilerExtension;

/**
 * @package   Klimesf\Mailgun\DI
 * @author    Filip Klimes <filipklimes@startupjobs.cz>
 * @copyright 2015, Startupedia s.r.o.
 */
class MailgunExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		if (!array_key_exists('key', $config) || !array_key_exists('domain', $config)) {
			throw new \UnexpectedValueException("Please configure the Mailgun extensions using the section '{$this->name}:' in your config file.");
		}

		$builder->addDefinition($this->prefix('mailgun'))
			->setClass('Mailgun\Mailgun', [$config['key']]);

		$builder->addDefinition($this->prefix('mailer'))
			->setClass('Klimesf\Mailgun\MailgunMailer', [$this->prefix('@mailgun'), $config['domain']]);
	}

}
