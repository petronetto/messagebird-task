<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SmsService;
use Core\Http\Controller;
use Core\Http\ResponseInterface;

class SmsController extends Controller
{
    /** @var ResponseInterface */
    protected $response;

    /** @var SmsService */
    protected $sms;

    /**
     * @param Response $response
     */
    public function __construct(ResponseInterface $response, SmsService $sms)
    {
        $this->response = $response;
        $this->sms      = $sms;
    }

    public function index()
    {
        return $this->response->view('Sms');
    }

    public function create()
    {
        $result = $this->sms->send(
            $_POST['originator'],
            [$_POST['recipient']],
            $_POST['message']
        );

        return $this->response->withJson([
            'response' => $result
        ]);

        // return $this->response->redirect('/')
        //             ->withStatusCode(201);
    }
}
