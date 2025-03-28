<?php

declare(strict_types=1);

namespace Neuralpin\HTTPRouter\Demo;

use Neuralpin\HTTPRouter\Helper\TemplateRender;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\RequestData;
use Neuralpin\HTTPRouter\Response;

class DemoController
{
    public function get(int $id): ResponseState
    {

        return Response::json([
            'id' => $id,
            'data' => [
                'id' => $id,
                'title' => 'Lorem ipsum',
                'price' => 123,
            ],
        ]);

    }

    public function list(): ResponseState
    {
        $ProductList = [
            [
                'id' => 123,
                'title' => 'Lorem ipsum',
                'price' => 123,
            ],
            [
                'id' => 321,
                'title' => 'Dolor sit',
                'price' => 312.99,
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
            ],
        ], 200);

    }

    public function delete(int $id): ResponseState
    {
        return Response::json([
            'status' => 'deleted',
            'data' => [
                'id' => $id,
            ],
        ], 200);
    }

    public function templating(RequestData $Request): ResponseState
    {
        // Add query param to the URL and capture it here ?text=lorem ipsum
        $URLData = htmlspecialchars($Request->getParam('text') ?? '');

        return Response::template(
            content: __DIR__.'/../../demo/template/page_template.php',
            context: [
                'title' => 'Dynamic page created using the most easiest template system',
                'content' => "
                    <p>Pariatur irure proident excepteur sunt irure sunt elit ex sint minim elit aliqua ea Lorem. Anim nisi quis duis aliqua pariatur eu anim fugiat. Non nostrud anim in quis laboris voluptate occaecat veniam aliqua. Ullamco eu occaecat officia adipisicing velit. Amet excepteur amet ut proident tempor duis.</p>
                    <p>Query Text: {$URLData}</p>
                ",
                'menu' => new TemplateRender(
                    filepath: __DIR__.'/../../demo/template/menu_template.php',
                    context: [
                        'links' => [
                            'index' => '#',
                            'about' => '#about',
                            'contact' => '#contact',
                        ],
                    ]
                ),
            ]
        );
    }
}
