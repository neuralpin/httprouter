<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\Helper\RequestDataHelper;
use Neuralpin\HTTPRouter\RequestData;
use PHPUnit\Framework\TestCase;

class RequestDataHelperTest extends TestCase
{
    public function test_get_query_params()
    {
        $queryString = 'param1=value1&param2=value2';
        $expected = [
            'param1' => 'value1',
            'param2' => 'value2',
        ];
        $this->assertEquals($expected, RequestDataHelper::getQueryParams($queryString));

        $queryString = 'param1=value1&param2=';
        $expected = [
            'param1' => 'value1',
            'param2' => '',
        ];
        $this->assertEquals($expected, RequestDataHelper::getQueryParams($queryString));

        $queryString = '';
        $expected = [];
        $this->assertEquals($expected, RequestDataHelper::getQueryParams($queryString));
    }

    public function test_get_all_headers()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $expected = [
            'Host' => 'localhost',
            'User-Agent' => 'PHPUnit',
        ];

        $this->assertEquals($expected, RequestDataHelper::getAllHeaders());
    }

    public function test_get_body_string()
    {
        $body = fn () => 'test body content';

        $expected = 'test body content';

        $this->assertEquals($expected, RequestDataHelper::getBodyString($body));
    }

    public function test_create_from_globals()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = 'test/path';
        $_SERVER['QUERY_STRING'] = 'param1=value1&param2=value2';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $Request = RequestDataHelper::createStateFromGlobals(RequestData::class, json_encode(['key' => 'value']));

        // file_put_contents('php://output', '11111111111'.json_encode($Request->getHeaders()).'32222222');

        $this->assertSame('post', $Request->getMethod());
        $this->assertSame('test/path', $Request->getPath());
        $this->assertSame(['param1' => 'value1', 'param2' => 'value2'], $Request->getQueryParams());
        $this->assertSame('application/json', $Request->getHeaders()['Content-Type']);
        $this->assertSame(['key' => 'value'], $Request->getBody());
    }
}
