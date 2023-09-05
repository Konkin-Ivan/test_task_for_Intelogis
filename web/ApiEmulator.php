<?php

namespace App;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ApiEmulator
{
    public static function createMockHandler()
    {
        return new MockHandler([
            new Response(200, [], json_encode(['price' => 150])),
            new Response(200, [], json_encode(['date' => '2022-01-01'])),
        ]);
    }

    public static function createHandlerStack()
    {
        $mock = self::createMockHandler();
        return HandlerStack::create($mock);
    }
}