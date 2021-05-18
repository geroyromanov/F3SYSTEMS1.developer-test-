<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CsvService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExport extends Controller {
    /**
     * Converts the user input into a CSV file and streams the file back to the user
     */
    public function convert(Request $request, CsvService $csvService): StreamedResponse
    {
        $callback = $csvService->putToCsv($request->all());

        return response()->stream($callback, 200, $csvService->getHeaders());
    }
}
