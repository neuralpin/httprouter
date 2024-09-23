<?php

namespace Neuralpin\HTTPRouter\Helper;

use Stringable;

class TextRender implements Stringable
{
    public function __construct(
        public string $content,
        public array $context = []
    ) {}

    public function render()
    {
        ob_start();
        extract($this->context);
        echo $this->content;

        return ob_get_clean();
    }

    public function __toString(): string
    {
        return (string) $this->render();
    }
}
