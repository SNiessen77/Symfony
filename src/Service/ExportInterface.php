<?php

namespace App\Service;

interface ExportInterface
{
    public function export(array $data): string;

    public function getFileType(): string;

    public function getFileName(): string;
}
