<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Nette,
	Tracy\Debugger;

class ErrorPresenter extends BasePresenter
{

	private static $messages = [
		0 => 'Server error with no cool message',
		404 => 'This page has been stolen!',
		500 => 'On no! My code is not working properly. It\'s disaster. But it\'s open source and you can fix it!',
	];

	/**
	 * @param \Exception $exception
	 * @return void
	 */
	public function renderDefault(\Exception $exception): void
	{
		Debugger::log($exception, Debugger::ERROR);
		$this->setView('error');

		$code = 500;
		if ($exception instanceof Nette\Application\BadRequestException) {
			$code = $exception->getCode();
		}

		$this->setVariables($code);
	}

	/**
	 * @param int $code
	 */
	private function setVariables(int $code): void
	{
		$index = 0;
		if (isset(self::$messages[$code])) {
			$index = $code;
		}

		$this->template->message = self::$messages[$index];
		$this->template->code = $code;
	}

}
