<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class ExportSerialize implements ExportInterface
{
    protected KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function export(array $data): string
    {
        $string = serialize($data);

        $filename = tempnam(
            $this->kernel->getProjectDir().'/var',
            'export-serialize-');
        file_put_contents($filename, $string);

        return $filename;
    }

    public function getFileType(): string
    {
        return 'text/plain';
    }

    public function getFileName(): string
    {
        return 'export-data.txt';
    }
}
