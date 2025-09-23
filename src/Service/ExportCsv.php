<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class ExportCsv
{
    protected KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function export(array $data): string
    {
        $lines = [];
        foreach ($data as $row) {
            $lines[] = implode(',', $row);
        }
        $string = implode("\n", $lines);
        $filename = tempnam(
            $this->kernel->getProjectDir().'/var',
            'export-csv-');
        file_put_contents($filename, $string);

        return $filename;
    }
}
