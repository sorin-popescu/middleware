<?php

namespace Middlewares\Action;

use League\Plates\Engine;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{
    /**
     * @var Engine
     */
    private $template;

    public function __construct()
    {
        $this->template = new Engine(__DIR__ . '/../templates/', 'phtml');
    }

    public function __invoke()
    {
        $data = [
            'data' => 'Homepage title'
        ];
        return new HtmlResponse(
            $this->template->render('home-page', $data),
            200
        );
    }
}
