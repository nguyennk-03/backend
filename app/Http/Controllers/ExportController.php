<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Jobs\ExportJsonJob;

class ExportController extends Controller
{
    public function queueExportJson()
    {
        ExportJsonJob::dispatch();
        return response()->json(['message' => 'Exporting JSON in background...']);
    }
}
