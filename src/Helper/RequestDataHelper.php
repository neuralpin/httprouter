<?php

declare(strict_types=1);

namespace Neuralpin\HTTPRouter\Helper;

use Neuralpin\HTTPRouter\Interface\RequestState;

class RequestDataHelper
{
    public static function getQueryParams(string $QueryString): array
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

    public static function getAllHeaders(): array
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

    /**
     * Get request input string from callable-input or using file_get_contents on php://input
     *
     * @param  callable(): string  $input
     */
    public static function getBodyString(null|object|array $input = null): string
    {
        $input ??= fn () => file_get_contents('php://input');

        return $input();
    }

    /**
     * Creates an instance of RequestState type filled with global server data
     *
     * @param  class-string<RequestState>  $RequestData
     */
    public static function createStateFromGlobals(string $RequestData, ?string $bodyRequestString = null): RequestState
    {
        $queryString = $_SERVER['QUERY_STRING'] ?? '';

        $bodyRequestString ??= self::getBodyString();

        $RequestState = new $RequestData;
        $RequestState->setHeaders(self::getAllHeaders());
        $RequestState->setBody(json_decode($bodyRequestString, true));
        $RequestState->setQueryParams(self::getQueryParams($queryString));
        $RequestState->setMethod($_SERVER['REQUEST_METHOD'] ?? 'get');
        $RequestState->setPath((string) strtok(trim($_SERVER['REQUEST_URI'] ?? '', '/') ?? '/', '?'));

        return $RequestState;
    }
}
