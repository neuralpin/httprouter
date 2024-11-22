<?php

use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test_json_response()
    {
        $content = ['key' => 'value'];
        $response = Response::json($content);
        $expectedHeaders = [
            'Content-Type: application/json',
        ];

        $this->assertInstanceOf(ResponseState::class, $response);
        $this->assertEquals($expectedHeaders, $response->getHeaders());
        $this->assertEquals(json_encode($content), $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function test_xml_response()
    {
        $content = '<root><key>value</key></root>';
        $response = Response::xml($content);
        $expectedHeaders = [
            'Content-Type: text/xml; charset=utf-8',
        ];

        $this->assertInstanceOf(ResponseState::class, $response);
        $this->assertEquals($expectedHeaders, $response->getHeaders());
        $this->assertEquals($content, $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function test_csv_response()
    {
        $content = "key,value\n";
        $response = Response::csv($content);
        $expectedHeaders = [
            'Content-Type: text/csv; charset=utf-8',
        ];

        $this->assertInstanceOf(ResponseState::class, $response);
        $this->assertEquals($expectedHeaders, $response->getHeaders());
        $this->assertEquals($content, $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function test_plain_response()
    {
        $content = 'plain text';
        $response = Response::plain($content);

        $this->assertInstanceOf(ResponseState::class, $response);
        $this->assertEquals($content, $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function test_html_response()
    {
        $content = '<html><body>content</body></html>';
        $response = Response::html($content);
        $expectedHeaders = [
            'Content-Type: text/html; charset=utf-8',
        ];

        $this->assertInstanceOf(ResponseState::class, $response);
        $this->assertEquals($expectedHeaders, $response->getHeaders());
        $this->assertEquals($content, $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function test_template_response()
    {
        $content = 'template content';

        $Response = Response::template(
            content: __DIR__.'/../../demo/template/template_render_test.php',
            context: [
                'content' => $content,
            ],
        );

        $templateRenderExpected = "<div>$content</div>";

        $this->assertInstanceOf(ResponseState::class, $Response);
        $this->assertEquals($templateRenderExpected, $Response->getBody());
    }

    public function test_template_not_found_response()
    {
        $this->expectException(\Exception::class);
        $Response = Response::template(__DIR__.'/../../demo/template/template_does_not_exists.php');
    }
}
