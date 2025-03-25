<?php

declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Kozak\Tomas\App\Model\AgeCalculator;
use Kozak\Tomas\App\Model\CaptchaDto;
use Kozak\Tomas\App\Model\CaptchaException;
use Kozak\Tomas\App\Model\CaptchaService;
use Kozak\Tomas\App\Model\LiveService;
use Kozak\Tomas\App\Model\Mailer;
use Kozak\Tomas\App\Model\MailerException;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Json;
use Tracy\Debugger;

final class HomepagePresenter extends BasePresenter
{

    public function __construct(
        private readonly AgeCalculator  $ageCalculator,
        private readonly Mailer         $mailer,
        private readonly CaptchaService $captchaService,
        private readonly LiveService    $liveService,
    )
    {
        parent::__construct();
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->age = $this->ageCalculator->getAge();
        $this->template->live = $this->liveService->isLive();
    }

    public function renderContact(): void
    {
        $captchaDto = $this->captchaService->getRandom();
        $captcha = $this
            ->getComponent('contactForm')
            ->getComponent('captchaSerialized');
        if ($captcha instanceof BaseControl) {
            $captcha->setValue($captchaDto->serialize());
        }

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
            if (!is_string($values->captchaSerialized)) {
                throw new CaptchaException("Captcha serialized is not string");
            }
            $captchaDto = CaptchaDto::deserialize($values->captchaSerialized);
        } catch (CaptchaException) {
            $form->addError('It looks like you are trying hack my website. Please try something better!');
            return;
        }

        if (!is_integer($values->captcha) || !$this->captchaService->isCorrect($captchaDto, $values->captcha)) {
            $form->addError('Your result is incorrect. Have you tried WolframAlpha?');
            return;
        }

        if (!is_string($values->name) || !is_string($values->email) || !is_string($values->content)) {
            $form->addError('Invalid form input data. Please try again.');
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
            "", // homepage
            "/resume",
            "/contact",
            "/my-setup",
            "/talks",
        ];

        $xml = new \SimpleXMLElement(
            data: '<urlset/>',
            namespaceOrPrefix: 'ws',
        );

        $xml->addAttribute('xmlns:xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($pages as $page) {
            $xml
                ->addChild("url")
                ->addChild("loc", "https://kozak.in" . $page);
        }


        $this->getHttpResponse()->setHeader("Content-Type", "application/xml");
        $this->sendResponse(new TextResponse($xml->asXML()));
    }

    public function actionLive(): void
    {
        if (!$this->getHttpRequest()->isMethod("POST")) {
            throw new BadRequestException('Method Not Allowed', 405);
        }

        if (!$this->getHttpRequest()->getHeader("Authorization")) {
            throw new BadRequestException('Unauthorized', 401);
        }

        $auth = \explode(" ", $this->getHttpRequest()->getHeader("Authorization"));
        if (count($auth) !== 2 || $auth[0] !== "Bearer" || !$this->liveService->isTokenValid($auth[1])) {
            throw new BadRequestException('Unauthorized', 401);
        }

        if (!$this->getHttpRequest()->getRawBody()) {
            throw new BadRequestException('Invalid JSON body');
        }

        try {
            $data = Json::decode($this->getHttpRequest()->getRawBody(), true);
            if (!is_array($data) || !isset($data['live']) || !is_bool($data['live'])) {
                throw new BadRequestException('Invalid JSON body');
            }

            $this->liveService->setLive($data['live']);
        } catch (\JsonException) {
            throw new BadRequestException('Invalid JSON body');
        }
        $this->getHttpResponse()->setCode(204);

        $this->terminate();
    }
}
