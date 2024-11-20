<?php

namespace Neuralpin\HTTPRouter\Helper;

class RequestDataHelper
{
    public function getQueryParams(string $QueryString): array
    {
        $params = [];
        if (! empty($QueryString)) {
            foreach (explode('&', $QueryString) as $chunk) {
                $param = explode('=', $chunk);
                if ($param) {
                    $param[1] ??= '';
                    $params[$param[0]] = rawurldecode($param[1]);
                }
            }
        }
        return $params;
    }

    public function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }

    public function getBodyString(): string
    {
        return file_get_contents('php://input');
    }
}
