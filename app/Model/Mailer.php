<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

use SendGrid\Mail\Mail;

final class Mailer
{

	private const
		EMAIL_FROM = 'site@kozak.in',
		EMAIL_FROM_NAME = 'Contact form',
		EMAIL_TO = 'kozak@talko.cz',
		EMAIL_SUBJECT = 'Contact from - Kozak.in',
		EMAIL_BODY = "<b>Name:</b> %s<br><b>Email:</b> %s<br><br>%s";

	/** @var string */
	private $sendgridApiKey;

	/**
	 * @param string $sendgridApiKey
	 */
	public function __construct(string $sendgridApiKey)
	{
		$this->sendgridApiKey = $sendgridApiKey;
	}

	/**
	 * @param string $name
	 * @param string $email
	 * @param string $content
	 * @throws MailerException
	 * @throws \SendGrid\Mail\TypeException
	 */
	public function contactFormEmail(string $name, string $email, string $content): void
	{
		$message = new Mail();
		$message->setFrom(self::EMAIL_FROM, self::EMAIL_FROM_NAME);
		$message->setSubject(self::EMAIL_SUBJECT);
		$message->addTo(self::EMAIL_TO, self::EMAIL_TO);
		$message->addContent(
			'text/html',
			\sprintf(self::EMAIL_BODY, $name, $email, $content)
		);
		$sendgrid = $this->getMailer();
		try {
			$sendgrid->send($message);
		} catch (\Exception $e) {
			throw new MailerException('Could not send email');
		}
	}

	private function getMailer(): \SendGrid
	{
		return new \SendGrid($this->sendgridApiKey);
	}
}
