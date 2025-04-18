<?php

declare(strict_types=1);

use Kozak\Tomas\App\Bootstrap;
use Nette\Application\IPresenterFactory;
use Nette\Application\Responses\TextResponse;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

/** @var Container $container */
$container = Bootstrap::boot();

/** @var IPresenterFactory $presenterFactory */
$presenterFactory = $container->getByType(IPresenterFactory::class);

final class UiTests extends TestCase {

    private IPresenterFactory $presenterFactory;

    public function __construct(IPresenterFactory $presenterFactory)
    {
        $this->presenterFactory = $presenterFactory;
    }

    public function testHomepage() 
    {
        $response = $this->sendRequest('Homepage', 'default', 'GET');
        $html = (string) $response->getSource();
        Assert::contains('Professional Details', $html);
        Assert::contains('Tomáš Kozák', $html);
        Assert::contains('Years', $html);
        Assert::contains('About Me', $html);
    }

    public function testResume() 
    {
        $response = $this->sendRequest('Homepage', 'resume', 'GET');
        $html = (string) $response->getSource();
        Assert::contains('ShipMonk', $html);
        Assert::contains('Kiwi.com', $html);
        Assert::contains('Dixons Carphone plc', $html);
        Assert::contains('Monitoring', $html);
        Assert::contains('Kubernetes', $html);
    }

    public function testContact() 
    {
        $response = $this->sendRequest('Homepage', 'contact', 'GET');
        $html = (string) $response->getSource();
        Assert::contains('SAY HELLO', $html);
        Assert::contains('Your message', $html);
        Assert::contains('wxfffqt', $html);
    }
    
    public function testSetup() 
    {
        $response = $this->sendRequest('Homepage', 'setup', 'GET');
        $html = (string) $response->getSource();
        Assert::contains('My setup', $html);
        // Assert::contains('Sony', $html);
    }

    public function testTalks() 
    {
        $response = $this->sendRequest('Homepage', 'talks', 'GET');
        $html = (string) $response->getSource();
        Assert::contains('USE DOCKER IN PRODUCTION', $html);
        Assert::contains('Pecka design, Brno', $html);
        Assert::contains('Tomáš Kozák', $html);
    }

    private function sendRequest(string $presenter, string $action, string $method) : TextResponse
    {
        $hp = $this->presenterFactory->createPresenter($presenter);
        $hp->autoCanonicalize = false;
        $request = new Nette\Application\Request($presenter, $action, ['action' => $action]);

        /** @var TextResponse $response */
        $response = $hp->run($request);
        if (!$response instanceof \Nette\Application\Responses\TextResponse) {
            Assert::fail(sprintf('Invalid response from %s:%s', $presenter, $action));
        }

        return $response;
    }
}

(new UiTests($presenterFactory))->run();