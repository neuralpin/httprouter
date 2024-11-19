<?php

namespace Neuralpin\HTTPRouter\Demo;

use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Helper\RequestData;
use Neuralpin\HTTPRouter\Interface\ResponseState;

class DemoController
{
    public function home(): ResponseState
    {
        return Response::template('template/home.html');
    }

    public function getById(int $id): ResponseState
    {

        return Response::json([
            'id' => $id,
            'data' => [],
        ]);

    }

    public function list(): ResponseState
    {
        $ProductList = [
            [
                'id' => 123,
                'title' => 'Lorem ipsum',
                'price' => 123
            ],
            [
                'id' => 321,
                'title' => 'Dolor sit',
                'price' => 312.99
            ],
            [
                'id' => 231,
                'title' => 'Amet consectetur',
                'price' => null,
            ],
        ];

        return Response::json([
            'count' => count($ProductList),
            'list' => array_values($ProductList),
        ]);
    }

    public function create(RequestData $Request): ResponseState
    {
        $title = $Request->getInput('title');
        $price = $Request->getInput('price') ?? 0;

        if (empty($title)) {
            return Response::json([
                'error' => 'Product title is required',
            ], 400);
        }

        return Response::json([
            'status' => 'created',
            'data' => [
                'id' => 323,
                'title' => $title,
                'price' => $price,
            ],
        ], 201);

    }

    public function update(int $id, RequestData $Request): ResponseState
    {

        $title = $Request->getInput('title') ?? null;
        $price = $Request->getInput('price') ?? null;


        return Response::json([
            'status' => 'updated',
            'data' => [
                'id' => $id,
                'title' => $title,
                'price' => $price,
            ]
        ], 200);

    }

    public function delete(int $id): ResponseState
    {
        return Response::json([
            'status' => 'deleted',
            'data' => [
                'id' => $id,
            ]
        ], 200);
    }
}
