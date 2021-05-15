<?php

namespace App\Services;

use Closure;

class CsvService
{
    public function putToCsv(array $data): Closure
    {
        return function() use($data) {
            $file = fopen('php://output', 'w');

            $columns = array_keys(reset($data));
            fputcsv($file, $columns);

            foreach ($data as $item) {
                fputcsv($file, $item);
            }

            fclose($file);
        };
    }

    public function getHeaders(): array
    {
        return [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$this->getFileName(),
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    }

    private function getFileName(): string
    {
        return "users (" . date('Y-m-d') . ").csv";
    }
}
