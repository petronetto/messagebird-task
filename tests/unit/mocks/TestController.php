<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Core\Http\Response;

class TestController
{
    public function index(string $id = "42", string $answer = "42")
    {
        return [$id, $answer];
    }

    public function retrunsString()
    {
        return 'The answer is 42';
    }

    public function retrunsInteger()
    {
        return 42;
    }

    public function retrunsDouble()
    {
        return 42.42;
    }

    public function retrunsResponse()
    {
        $res = (new Response)->view(
            'Test',
            ['answer' => 42],
            base_path('tests/unit/mocks/views')
        );

        return $res;
    }
}
