<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SmsService;
use Core\Http\Controller;
use Core\Http\ResponseInterface;

class HomeController extends Controller
{
    /** @var ResponseInterface */
    protected $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response  = $response;
    }

    public function index()
    {
        return $this->response->view('Home');
    }
}
