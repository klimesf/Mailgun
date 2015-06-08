Quickstart
==========

Integration of [Mailgun](https://github.com/mailgun/mailgun-php) into Nette Framework.


Installation
------------

The best way to install Klimesf/Mailgun is using [Composer](http://getcomposer.org/):

```sh
$ composer require klimesf/mailgun
```

and enable it in `config.neon`

```yml
extensions:
	mailgun: Klimesf\Mailgun\DI\MailgunExtension
```


Minimal configuration
---------------------

This extension creates new configuration section `mailgun`.
You have to setup your domain and key.

```yml
mailgun:
	key: 'my-secret-key'
	domain: 'my-domain'
```

Sending Nette/Mail/Message
--------------------------

This extension let's you easily send instances of `Nette/Mail/Message` via Mailgun service.

Require `Klimesf/Mailgun/MailgunMailer` via dependency injection and let Nette inject it.

```php

use Nette;
use Klimesf;

class MailPresenter
{

	/** @var Klimesf\Mailgun\MailgunMailer @inject */
	public $mailgunMailer;
	
	// ...

}
```

Then just simply send the mail.

```php

$mail = new Nette\Mail\Message();
$mail->setFrom('...');
$mail->addTo('...');

$result = $this->mailgunMailer->send($mail);

```

