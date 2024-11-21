<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\Helper\RequestData;

require __DIR__.'/../vendor/autoload.php';

class RequestDataTest extends TestCase
{

    public function testGetHeaders()
    {
        $headers = ['Content-Type' => 'application/json'];
        $Request = new RequestData();
        $Request->setHeaders($headers);
        $this->assertSame($headers, $Request->getHeaders());
    }

    public function testGetBody()
    {
        $body = ['key' => 'value'];
        $Request = new RequestData();
        $Request->setBody($body);
        $this->assertSame($body, $Request->getBody());
    }

    public function testGetQueryParams()
    {
        $queryParams = ['param1' => 'value1'];
        $Request = new RequestData();
        $Request->setQueryParams($queryParams);
        $this->assertSame($queryParams, $Request->getQueryParams());
    }

    public function testGetMethod()
    {
        $method = 'post';
        $Request = new RequestData();
        $Request->setMethod($method);
        $this->assertSame($method, $Request->getMethod());
    }

    public function testGetPath()
    {
        $path = '/test/path';
        $Request = new RequestData();
        $Request->setPath($path);
        $this->assertSame($path, $Request->getPath());
    }
}