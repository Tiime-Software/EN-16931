<?php

namespace Tiime\EN16931\Codelist\Generator;

/**
 * @internal
 */
final readonly class Generator
{
    private const string ENUM_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

namespace <namespace>;

enum <className> : string
{
<cases>
}
TEMPLATE;

    public function generateCodelist(
        string $className,
        XlsxReaderResult $cases
    ): void {
        $code = [];

        /** @var XlsxReaderResultItem $case */
        foreach ($cases->getHarmonized() as $case) {
            $code[] = (string) $case;
        }

        $replacements = [
            '<cases>' => '        ' . implode("\n        ", $code),
            '<namespace>' => 'Tiime\EN16931\Codelist',
            '<className>' => $className
        ];

        $code = strtr(self::ENUM_TEMPLATE, $replacements);
        $code = preg_replace('/^ +$/m', '', $code);
        $path            = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $className . '.php';

        file_put_contents($path, $code);
    }
}
