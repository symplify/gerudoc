<?php

declare(strict_types=1);

namespace Symplify\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter;

use Iterator;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\RuleDocGenerator\DirectoryToMarkdownPrinter;
use Symplify\RuleDocGenerator\HttpKernel\RuleDocGeneratorKernel;
use Symplify\SmartFileSystem\SmartFileInfo;

final class DirectoryToMarkdownPrinterTest extends AbstractKernelTestCase
{
    /**
     * @var DirectoryToMarkdownPrinter
     */
    private $directoryToMarkdownPrinter;

    protected function setUp(): void
    {
        $this->bootKernel(RuleDocGeneratorKernel::class);
        $this->directoryToMarkdownPrinter = self::$container->get(DirectoryToMarkdownPrinter::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $directoryFileInfo, string $expectedFile): void
    {
        $fileContent = $this->directoryToMarkdownPrinter->printDirectory($directoryFileInfo);
        $this->assertStringEqualsFile($expectedFile, $fileContent);
    }

    public function provideData(): Iterator
    {
        yield [new SmartFileInfo(__DIR__ . '/Fixture/PHPStan'), __DIR__ . '/Expected/phpstan_content.md'];
        yield [new SmartFileInfo(__DIR__ . '/Fixture/PHPCSFixer'), __DIR__ . '/Expected/phpcsfixer_content.md'];
        yield [
            new SmartFileInfo(__DIR__ . '/Fixture/ConfigurablePHPCSFixer'),
            __DIR__ . '/Expected/configurable_phpcsfixer_content.md',
        ];
    }
}