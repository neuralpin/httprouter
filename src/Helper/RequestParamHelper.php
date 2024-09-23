<?php

namespace Neuralpin\HTTPRouter\Helper;

class RequestParamHelper
{
    public ?array $Params = [];

    public function __construct(public readonly string $QueryString)
    {
        if (! empty($QueryString)) {
            foreach (explode('&', $QueryString) as $chunk) {
                $param = explode('=', $chunk);
                if ($param) {
                    $param[1] ??= '';
                    $this->Params[$param[0]] = rawurldecode($param[1]);
                }
            }
        }
    }

    public function get(string $key): mixed
    {
        return $this->Params[$key] ?? null;
    }
}
