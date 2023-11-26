<?php

declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Kozak\Tomas\App\Model\AgeCalculator;
use Kozak\Tomas\App\Model\CaptchaDto;
use Kozak\Tomas\App\Model\CaptchaException;
use Kozak\Tomas\App\Model\CaptchaService;
use Kozak\Tomas\App\Model\Mailer;
use Kozak\Tomas\App\Model\MailerException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Tracy\Debugger;

final class HomepagePresenter extends BasePresenter
{

    public function __construct(
        private readonly AgeCalculator  $ageCalculator,
        private readonly Mailer         $mailer,
        private readonly CaptchaService $captchaService,
    )
    {
        parent::__construct();
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->age = $this->ageCalculator->getAge();
    }

    public function renderContact(): void
    {
        $captchaDto = $this->captchaService->getRandom();
        $this
            ->getComponent('contactForm')
            ->getComponent('captchaSerialized')
            ->setValue($captchaDto->serialize());

        $this->template->captcha = [
            'd0' => $captchaDto->d0,
            'd3' => $captchaDto->d3,
            'lower' => $captchaDto->lowerLimit,
            'upper' => $captchaDto->upperLimit,
        ];
    }

    public function createComponentContactForm(): Form
    {
        $form = new Form();
        $form->addText('name', 'Name')
            ->setMaxLength(200);
        $form->addText('email', 'Email')
            ->setMaxLength(200);
        $form->addTextArea('content', 'Content')
            ->setMaxLength(2000)
            ->setRequired('Write something.')
            ->addRule(Form::MIN_LENGTH, 'Your message has to be at least %d characters long', 5);
        $form->addInteger('captcha', 'Result:')
            ->setRequired('Please calculate definite integral')
            ->addRule(Form::RANGE, 'Really?', [0, 999999]);
        $form->addHidden('captchaSerialized');
        $form->addSubmit('send', 'SEND MESSAGE');
        $form->addProtection('Please submit form once again.');
        $form->onSuccess[] = function (Form $form): void {
            $this->contactFormSubmitted($form);
        };

        return $form;
    }

    private function contactFormSubmitted(Form $form): void
    {
        $values = $form->getValues();

        try {
            $captchaDto = CaptchaDto::deserialize($values->captchaSerialized);
        } catch (CaptchaException $exception) {
            $form->addError('It looks like you are trying hack my website. Please try something better!');
            return;
        }

        if (!$this->captchaService->isCorrect($captchaDto, (int)$values->captcha)) {
            $form->addError('Your result is incorrect. Have you tried WolframAlpha?');
            return;
        }

        try {
            $this->mailer->sendMessage($values->name, $values->email, $values->content);
        } catch (MailerException $exception) {
            $form->addError('Could not send message to Tomas. Please try to contact him via email.');
            return;
        }
        $this->flashMessage('Wonderful job! Thank You. Your Message has been submitted and I will respond ASAP!');
        $this->redirect('this');
    }

    public function actionSpeeches(): void
    {
        Debugger::log("Speeches called - deprecated");
        $this->redirectPermanent('talks');
    }

    public function actionRobots(): void
    {
        $robotsTxt = "User-agent: *\nAllow: /";
        $this->sendResponse(new TextResponse($robotsTxt));
    }

    public function actionSitemap(): void
    {
        $pages = [
            "https://kozak.in",
            "https://kozak.in/resume",
            "https://kozak.in/contact",
            "https://kozak.in/my-setup",
            "https://kozak.in/talks",
        ];

        $xml = new \SimpleXMLElement(
            data: '<urlset/>',
            namespaceOrPrefix: 'ws',
        );

        $xml->addAttribute('xmlns:xmlns', 'http://url.to.namespace');

        foreach ($pages as $page) {
            $xml
                ->addChild("url")
                ->addChild("loc", $page);
        }


        $this->getHttpResponse()->setHeader("Content-Type", "application/xml");
        $this->sendResponse(new TextResponse($xml->asXML()));
    }
}
