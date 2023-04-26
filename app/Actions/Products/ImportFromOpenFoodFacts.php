<?php

namespace App\Actions\Products;

use App\Actions\ProcessJobs;
use App\Jobs\{DowloadProductsJob, ImportProductsJob};
use Illuminate\Support\Facades\{Bus, Http};
use Illuminate\Support\Str;

class ImportFromOpenFoodFacts
{
    private string $baseUrl = "https://challenges.coode.sh/food/data/json/";

    private array $fileNames = [];

    public function run()
    {
        $this->getFileNames();

        $jobs = [];

        foreach ($this->fileNames as $fileName) {
            $streamPath = $this->baseUrl . $fileName . '.json.gz';
            $jobs[]     = [
                new DowloadProductsJob($streamPath, $fileName . '.zip'),
                new ImportProductsJob(base_path('/Cron/Products/' . $fileName . '.zip')),
            ];
        }

        ProcessJobs::handle($jobs, "Import-products");
    }

    private function getFileNames()
    {
        $response = Http::get($this->baseUrl . 'index.txt')->body();
        $names    = Str::of($response)->explode('.json.gz');

        foreach ($names as $name) {
            if (strlen($name) > 1) {
                if ($name[0] !== "p") {
                    $this->fileNames[] = substr($name, 1);
                } else {
                    $this->fileNames[] = $name;
                }
            }
        }
    }
}
