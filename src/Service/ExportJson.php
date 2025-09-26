<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class ExportJson implements ExportInterface
{
    protected KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function export(array $data): string
    {
        $string = json_encode($data);

        $filename = tempnam(
            $this->kernel->getProjectDir().'/var',
            'export-json-');
        file_put_contents($filename, $string);

        return $filename;
    }

    public function getFileType(): string
    {
        return 'application/json';
    }

    public function getFileName(): string
    {
        return 'export-data.json';
    }
}
