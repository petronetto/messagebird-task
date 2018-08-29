<?php

declare(strict_types=1);

namespace Tests;

use Core\Http\Response;

class ResponseTest extends BaseTest
{
    public function test_get_body(): void
    {
        $res = (new Response)->setBody(42);

        $this->assertEquals(42, $res->getBody());
    }

    public function test_view(): void
    {
        $res = (new Response)->view(
            'Test',
            ['answer' => 42],
            base_path('tests/unit/mocks/views')
        );

        $this->assertEquals(
            'The answer for everything: 42',
            $res->getBody()
        );
    }

    public function test_with_json(): void
    {
        $res = (new Response)->withJson([
            'answer' => 42,
        ]);

        $this->assertEquals(
            json_encode(['answer' => 42]),
            $res->getBody()
        );

        $this->assertEquals(
            [['Content-Type', 'application/json']],
            $res->getHeaders()
        );
    }

    public function test_with_status_code(): void
    {
        $res = (new Response)->withStatusCode(404);

        $this->assertEquals(404, $res->getStatusCode());
    }
}
