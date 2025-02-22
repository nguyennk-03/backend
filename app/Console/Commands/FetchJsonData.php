<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchJsonData extends Command
{
    protected $signature = 'fetch:json';
    protected $description = 'Fetch JSON from API and save to storage';

    public function handle()
    {
        $response = Http::get('https://example.com/api/data');

        if ($response->failed()) {
            $this->error('Failed to fetch data');
            return;
        }

        Storage::put('data.json', json_encode($response->json(), JSON_PRETTY_PRINT));
        $this->info('JSON data saved successfully');
    }
}
