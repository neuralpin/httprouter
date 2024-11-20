<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\Helper\RequestData;

require __DIR__.'/../vendor/autoload.php';

class RequestDataTest extends TestCase
{

    public function testGetHeaders()
    {
        $headers = ['Content-Type' => 'application/json'];
        $Request = new RequestData(headers: $headers);
        $this->assertSame($headers, $Request->getHeaders());
    }

    public function testGetBody()
    {
        $body = ['key' => 'value'];
        $Request = new RequestData(body: $body);
        $this->assertSame($body, $Request->getBody());
    }

    public function testGetQueryParams()
    {
        $queryParams = ['param1' => 'value1'];
        $Request = new RequestData(queryParams: $queryParams);
        $this->assertSame($queryParams, $Request->getQueryParams());
    }

    public function testGetMethod()
    {
        $method = 'post';
        $Request = new RequestData(method: $method);
        $this->assertSame($method, $Request->getMethod());
    }

    public function testGetPath()
    {
        $path = '/test/path';
        $Request = new RequestData(path: $path);
        $this->assertSame($path, $Request->getPath());
    }

    public function testCreateFromGlobals()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = 'test/path';
        $_SERVER['QUERY_STRING'] = 'param1=value1&param2=value2';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';
        $Request = RequestData::createFromGlobals(json_encode(['key' => 'value']));

        $this->assertSame('post', $Request->getMethod());
        $this->assertSame('test/path', $Request->getPath());
        $this->assertSame(['param1' => 'value1', 'param2' => 'value2'], $Request->getQueryParams());
        $this->assertSame(['Content-Type' => 'application/json'], $Request->getHeaders());
        $this->assertSame(['key' => 'value'], $Request->getBody());
    }
}