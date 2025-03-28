<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\RequestData;
use PHPUnit\Framework\TestCase;

class RequestDataTest extends TestCase
{
    public function test_get_headers()
    {
        $headers = ['Content-Type' => 'application/json'];
        $Request = new RequestData;
        $Request->setHeaders($headers);
        $this->assertSame($headers, $Request->getHeaders());
    }

    public function test_get_body()
    {
        $body = ['key' => 'value'];
        $Request = new RequestData;
        $Request->setBody($body);
        $this->assertSame($body, $Request->getBody());
    }

    public function test_get_query_params()
    {
        $queryParams = ['param1' => 'value1'];
        $Request = new RequestData;
        $Request->setQueryParams($queryParams);
        $this->assertSame($queryParams, $Request->getQueryParams());
    }

    public function test_get_method()
    {
        $method = 'post';
        $Request = new RequestData;
        $Request->setMethod($method);
        $this->assertSame($method, $Request->getMethod());
    }

    public function test_get_path()
    {
        $path = '/test/path';
        $Request = new RequestData;
        $Request->setPath($path);
        $this->assertSame($path, $Request->getPath());
    }
}
