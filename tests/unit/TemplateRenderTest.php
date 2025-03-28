<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\Helper\TemplateRender;
use PHPUnit\Framework\TestCase;

class TemplateRenderTest extends TestCase
{
    protected string $testFilePath;

    protected array $testContext;

    protected function setUp(): void
    {
        $this->testFilePath = __DIR__.'/test_template.php';
        $this->testContext = ['key' => 'value'];

        // Create a temporary test template file
        file_put_contents($this->testFilePath, '<?php echo $key; ?>');
    }

    protected function tearDown(): void
    {
        // Remove the temporary test template file
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function test_constructor_throws_exception_for_missing_file()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found: non_existent_file.php');

        new TemplateRender('non_existent_file.php');
    }

    public function test_render()
    {
        $templateRender = new TemplateRender($this->testFilePath, $this->testContext);
        $output = $templateRender->render();

        $this->assertEquals('value', $output);
    }

    public function test_to_string()
    {
        $templateRender = new TemplateRender($this->testFilePath, $this->testContext);
        $output = (string) $templateRender;

        $this->assertEquals('value', $output);
    }
}
