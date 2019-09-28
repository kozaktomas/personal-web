<?php

namespace Kozak\Tomas\App\Presenters;


use Kozak\Tomas\App\Model\Mailer;
use Kozak\Tomas\App\Model\MailerException;
use Nette\Application\UI\Form;

class HomepagePresenter extends BasePresenter
{

	/** @var Mailer */
	private $mailer;

	/**
	 * @param Mailer $mailer
	 */
	public function __construct(Mailer $mailer)
	{
		parent::__construct();
		$this->mailer = $mailer;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->age = $this->getAge();
		$this->template->googleAnalytics = !(bool)\getenv('DEV');
	}

	/**
	 * @return Form
	 */
	public function createComponentContactForm(): Form
	{
		$form = new Form();
		$form->addText('name', 'Name')
			->setMaxLength(200);
		$form->addText('email', 'Email')
			->setMaxLength(200);
		$form->addTextArea('content', 'Content')
			->setMaxLength(5000)
			->setRequired('Write something.')
			->addRule(Form::MIN_LENGTH, 'Your message has to be at least %d characters long', 5);
		$form->addSubmit('send', 'SEND MESSAGE');
		$form->onSuccess[] = function () use ($form) {
			$this->contactFormSubmitted($form);
		};

		return $form;
	}

	/**
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 * @throws \SendGrid\Mail\TypeException
	 */
	private function contactFormSubmitted(Form $form): void
	{
		$values = $form->values;
		try {
			$this->mailer->contactFormEmail($values->name, $values->email, $values->content);
		} catch (MailerException $exception) {
			$this->flashMessage('Could not send message to Tomas. Please try to contact him via email.');
			$this->redirect('this');
		}
		$this->flashMessage('Thank You. Your Message has been Submitted');
		$this->redirect('this');
	}

	/**
	 * @return int
	 * @throws \Exception
	 */
	private function getAge(): int
	{
		$tz = new \DateTimeZone('Europe/Prague');
		$born = \DateTime::createFromFormat('d/m/Y', '21/04/1991', $tz);
		$now = new \DateTime('now', $tz);

		if (!$born instanceof \DateTime) {
			throw new \Exception('Could not parse date of birth.');
		}
		if (!$born instanceof \DateTime) {
			throw new \Exception('Could not create now DateTime.');
		}

		$age = $born->diff($now)->y;
		return $age;
	}
}
