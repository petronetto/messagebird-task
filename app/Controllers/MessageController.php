<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Controller;
use Core\Http\Response;

class MessageController extends Controller
{
    /** @var Response */
    protected $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index(string $msg = 'welcome')
    {
        return $this->response->view('Message', [
            'msg' => $msg,
            'test' => 'This is a test',
        ]);
    }
}
