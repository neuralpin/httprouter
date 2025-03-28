<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\ResponseRender;
use PHPUnit\Framework\TestCase;

class ResponseRenderTest extends TestCase
{
    public function test_constructor()
    {
        $body = 'Test body';
        $status = 201;
        $headers = ['Content-Type: application/json'];
        $response = new ResponseRender($body, $status, $headers);

        $this->assertEquals($body, $response->getBody());
        $this->assertEquals($status, $response->getStatus());
        $this->assertEquals($headers, $response->getHeaders());
    }

    public function test_set_and_get_params()
    {
        $params = ['key' => 'value'];
        $response = (new ResponseRender)->setParams($params);
        $this->assertEquals($params, $response->getParams());
    }

    public function test_set_and_get_query_params()
    {
        $queryParams = ['query' => 'value'];
        $response = (new ResponseRender)->setQueryParams($queryParams);
        $this->assertEquals($queryParams, $response->getQueryParams());
    }

    public function test_set_and_get_method()
    {
        $method = 'post';
        $response = (new ResponseRender)->setMethod($method);
        $this->assertEquals($method, $response->getMethod());
    }

    public function test_set_and_get_path()
    {
        $path = 'test/path';
        $response = (new ResponseRender)->setPath($path);
        $this->assertEquals($path, $response->getPath());
    }

    public function test_set_and_get_headers()
    {
        $headers = ['Content-Type: text/plain'];
        $response = (new ResponseRender)->setHeaders($headers);
        $this->assertEquals($headers, $response->getHeaders());
    }

    public function test_set_and_get_body()
    {
        $body = 'New body content';
        $response = (new ResponseRender)->setBody($body);
        $this->assertEquals($body, $response->getBody());
    }

    public function test_set_and_get_status()
    {
        $status = 404;
        $response = (new ResponseRender)->setStatus($status);
        $this->assertEquals($status, $response->getStatus());
    }

    public function test_to_string()
    {
        $body = 'String body';
        $response = new ResponseRender($body);
        $this->assertEquals($body, (string) $response);
    }
}
