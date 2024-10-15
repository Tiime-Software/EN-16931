#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php'; // Ensure autoload is included

use Tiime\EN16931\Codelist\Generator\CodelistGenerator;

// Call the generateCodelists method
CodelistGenerator::generateCodelists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests/EN16931_code_lists_values_v14-used_from_2024-11-15.xlsx');
