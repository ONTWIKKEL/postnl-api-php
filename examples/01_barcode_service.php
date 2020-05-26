<?php

declare(strict_types=1);

/**
 * The MIT License (MIT).
 *
 * Copyright (c) 2017-2020 Michael Dekker (https://github.com/firstred)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <git@michaeldekker.nl>
 * @copyright 2017-2020 Michael Dekker
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

use Firstred\PostNL\PostNL;
use Firstred\PostNL\PostNLFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;

// Autoloader
require_once '../vendor/autoload.php';

// Load all environment variables
$dotenv = new Dotenv(true);
if (file_exists('./.env')) {
    $dotenv->load('./.env');
} else {
    $dotenv->load('../.env.example');
}

/** @var PostNL $postnl */
$postnl = PostNLFactory::create(false);

$console = new Application();

$console
    ->setDefaultCommand('generate:barcode')
    ->register('generate:barcode')
    ->setDefinition([
        new InputArgument('type', InputArgument::OPTIONAL, 'Barcode type (3S, CX, etc.)', '3S'),
    ])
    ->setDescription('Generate a barcode.')
    ->setHelp('
The <info>generate:barcode</info> command will generate a barcode.
 
<comment>Samples:</comment>
  To run with default options:
    <info>php 01_barcode_service.php generate:barcode</info>
  To generate a different type of barcode
    <info>php console.php generate:barcode CX</info>
')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($postnl) {
        $type = $input->getArgument('type');
        $barcode = $postnl->generateBarcode($type);
        $output->writeln("The generated $type barcode is: <info>$barcode</info>");
    });

$console
    ->register('generate:barcode:country')
    ->setDefinition([
        new InputArgument('country', InputArgument::REQUIRED, 'Country code (NL, DE, etc.)'),
    ])
    ->setDescription('Generate a barcode for a specific country.')
    ->setHelp('
The <info>generate:barcode</info> command will generate a barcode for a specific country.
 
<comment>Samples:</comment>
  To generate a barcode for a domestic shipment:
    <info>php 01_barcode_service.php generate:barcode NL</info>
')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($postnl) {
        $country = strtoupper((string) $input->getArgument('country'));
        $barcode = $postnl->generateBarcodeByCountryCode($country);
        $output->writeln("The generated barcode for $country: <info>$barcode</info>");
    });

$console
    ->register('generate:barcodes')
    ->setDefinition([
        new InputOption('country', 'c', InputOption::VALUE_OPTIONAL, 'Country code (NL, DE, US, etc.)', 'NL'),
        new InputOption('amount', 'a', InputOption::VALUE_OPTIONAL, 'Amount of barcodes', '1'),
    ])
    ->setDescription('Generate multiple barcodes.')
    ->setHelp('
The <info>generate:barcodes</info> command will generate multiple barcodes.

<comment>Samples:</comment>
  To run with default options:
    <info>php 01_barcode_service.php generate:barcodes</info>
  To generate barcodes for a specific country
    <info>php console.php generate:barcodes --country=NL</info>
  To generate multiple barcodes for a specific country
    <info>php console.php generate:barcodes --country=NL --amount=3</info>
  To generate multiple barcodes for multiple countries (with amounts 3 & 5 resp.)
    <info>php console.php generate:barcodes --country=NL,DE --amount=3,5</info>
')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($postnl) {
        $io = new SymfonyStyle($input, $output);

        $countries = (string) $input->getOption('country');
        if (!strlen($countries)) {
            $io->getErrorStyle()->error('Please specify at least one country.');

            return 1;
        }

        $countries = explode(',', $countries);
        $amounts = explode(',', (string) $input->getOption('amount'));

        if (count($countries) !== count($amounts)) {
            $io->getErrorStyle()->error('Amount of countries and amount of amounts do not match.');

            return 1;
        }
        $tableBarcodes = [];
        $first = true;
        $generatedBarcodes = $postnl->generateBarcodesByCountryCodes(array_combine($countries, $amounts));
        foreach ($generatedBarcodes as $country => $barcodes) {
            if (!$first) {
                $tableBarcodes[] = new TableSeparator();
            }
            $first = false;
            $tableBarcodes[] = [$country, implode("\n", $barcodes)];
        }
        $io->writeln('These are the generated barcodes:');
        $table = new Table($io);
        $table->setStyle('borderless');
        $table
            ->setHeaders(['Country', 'Barcode(s)'])
            ->setRows($tableBarcodes)
        ;
        $table->render();

        return 0;
    });

$console->run();
