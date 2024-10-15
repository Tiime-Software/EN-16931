<?php

namespace Tiime\EN16931\Codelist\Generator;

use ArrayObject;

final class XlsxReaderResult
{
    /**
     * @var XlsxReaderResultItem[]
     */
    private array $result;

    public function add(XlsxReaderResultItem $item): void
    {
        $this->result[] = $item;
    }

    /**
     * @return XlsxReaderResultItem[]
     */
    public function get(): array
    {
        return $this->result;
    }

    /**
     * @return XlsxReaderResultItem[]
     */
    public function getHarmonized(): array
    {
        $nameCount = [];
        $harmonizedResult = [];

        foreach ($this->result as $item) {
            $name = $item->name;

            $name = preg_replace('/§\s*27/', '_', $name); // Specific ICD sheet
            assert(is_string($name));
            $name = preg_replace('/®/', '_', $name);
            assert(is_string($name));
            $name = preg_replace('/@/', 'A', $name);
            assert(is_string($name));

            $replacements = [
                '–' => '_',
                '>' => 'GREATER',  // Replace > with GREATER
                '%' => 'PERCENT',  // Replace % with PERCENT
                '+' => 'PLUS',     // Replace + with PLUS
                '°' => 'DEGREE'    // Replace ° with DEGREE
            ];

            $name = strtr($name, $replacements);

            $name = preg_replace('/[()]/', '', $name); // Remove parentheses
            assert(is_string($name));
            $name = preg_replace('/\s*\[.*?\]\s*/', '', $name); // Remove brackets and content inside
            assert(is_string($name));
            $name = preg_replace('/-/', '_', $name); // Replace - with underscores
            assert(is_string($name));
            $name = preg_replace('/,/', '_', $name); // Replace , with underscores
            assert(is_string($name));
            $name = preg_replace('/&/', '_', $name); // Replace & with underscores
            assert(is_string($name));
            $name = preg_replace('/\//', '_', $name); // Replace / with underscores
            assert(is_string($name));
            $name = preg_replace('/[.\'“”’:=*]/u', '', $name);
            assert(is_string($name));

            $name = trim($name, '_'); // Ensure the result does not start or end with an underscore

            $name = preg_replace('/^(\d)/', '_$1', $name); // Add underscore at the beginning if starting by a number
            assert(is_string($name));

            // Convert accented characters to non-accented
            $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
            assert(is_string($name));
            $name = mb_strtoupper($name);
            assert(is_string($name));
            $name = preg_replace('/\s+/', '_', $name); // Replace spaces with underscores
            assert(is_string($name));
            $name = preg_replace('/_+/', '_', $name); // Replace multiple underscores with a single underscore
            assert(is_string($name));

            // Initialize the count for this name if it doesn't exist
            if (!isset($nameCount[$name])) {
                $nameCount[$name] = 0;
            }

            $nameCount[$name]++;

            if ($nameCount[$name] > 1) {
                $name .= '_' . $this->getSuffix($nameCount[$name]);
            }

            $harmonizedResult[] = new XlsxReaderResultItem(name: $name, value: $item->value);
        }

        return $harmonizedResult;
    }

    private function getSuffix(int $count): string
    {
        return match ($count) {
            2 => 'SECOND',
            3 => 'THIRD',
            default => $count . 'TH',
        };
    }
}
