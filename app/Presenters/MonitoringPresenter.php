<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Kozak\Tomas\App\Model\Monitor;
use Nette\Application\Responses\CallbackResponse;
use Nette\Http\IRequest;
use Nette\Http\Response;
use Prometheus\RenderTextFormat;

class MonitoringPresenter extends BasePresenter
{

    public function __construct(
        private Monitor $monitor,
    )
    {
        parent::__construct();
    }

    public function actionDefault(): void
    {
        $output = $this->monitor->generateTextOutput();
        $this->sendResponse(new CallbackResponse(
                function (IRequest $request, Response $response) use ($output): void {
                    $response->setContentType(RenderTextFormat::MIME_TYPE);
                    echo $output;
                }
            )
        );
    }
}