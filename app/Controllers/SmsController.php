<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SmsService;
use App\Validations\Validator;
use Core\Exceptions\ValidationException;
use Core\Http\Controller;
use Core\Http\ResponseInterface;

class SmsController extends Controller
{
    /** @var ResponseInterface */
    protected $response;

    /** @var SmsService */
    protected $sms;

    /**
     * @param ResponseInterface $response
     * @param SmsService        $sms
     */
    public function __construct(ResponseInterface $response, SmsService $sms)
    {
        $this->response  = $response;
        $this->sms       = $sms;
    }

    public function index()
    {
        return $this->response->view('Sms');
    }

    public function create()
    {
        $this->validate($_REQUEST);

        $result = $this->sms->send(
            $_POST['originator'],
            $this->extractRecipents($_POST['recipient']),
            $_POST['message']
        );

        return $this->response->withJson([
            'response' => $result
        ]);
    }

    /**
     * @param  array $request
     * @return void
     */
    protected function validate(array $request): void
    {
        $errors = [];

        $recipient = new Validator;
        $phones    = $this->extractRecipents($request['recipient']);

        $recipient->isString()->isRequired();

        foreach ($phones as $phone) {
            $recipient->withInput($phone);

            if (!$recipient->isValid()) {
                $errors[] = 'recipient';
            }
        }

        $originator = new Validator;
        $originator->isString()
            ->isRequired()
            ->withInput($request['originator']);

        if (!$originator->isValid()) {
            $errors[] = 'originator';
        }

        $message = new Validator;
        $message->isString()
            ->isRequired()
            ->withInput($request['message']);

        if (!$message->isValid()) {
            $errors[] = 'message';
        }

        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @param  string $recipient
     * @return array
     */
    protected function extractRecipents(string $recipient): array
    {
        $recipient = str_replace(' ', '', $recipient);

        return explode(',', trim($recipient, ','));
    }
}
