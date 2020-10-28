<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Savitar\Files\SavitarFile;

class S3TableSeeder extends Seeder
{
    protected $importedCount = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        $uploadedUrls = [];
        echo "Migrando archivos a S3...\n";
        SavitarFile::orderBy('name')->chunk(500, function ($files) use ($uploadedUrls, $arrContextOptions) {
            $chunkStartTime = microtime(true);
            $chunkStart = $this->importedCount;
            foreach ($files as $file) {
                // Download the file, save to S3 and change to the new path
                if (strpos($file->path, 'https://') === 0) {
                    // Check if file is already uploaded to S3
                    if (in_array($file->url, $uploadedUrls, true)) {
                        $existingFile = SavitarFile::where('url', $file->url)->first();
                        if ($existingFile) {
                            $file->path = $existingFile->path;
                            $file->url = $existingFile->url;
                            $file->extension = $existingFile->extension;
                            $file->save();
                        }
                    } else {
                        try {
                            // Upload the file if not already uploaded
                            $downloadedFile = file_get_contents($file->url, false, stream_context_create($arrContextOptions));
                            $extension = pathinfo(parse_url($file->url, PHP_URL_PATH), PATHINFO_EXTENSION);
                            // echo "Descargado el archivo: " . $file->url . ".\n";
                            Storage::put('public/' . $file->id . '.' . $extension, $downloadedFile);
                            $file->path = 'public/' . $file->id . '.' . $extension;
                            $file->url = Storage::url($file->path);
                            $file->extension = $extension;
                            $file->save();

                            $uploadedUrls[] = $file->url;
                            // echo "Nueva url del archivo: " . $file->url . ".\n";
                        } catch (Exception $exception) {
                            $uploadedUrls[] = $file->url;
                            echo "No se ha podido descargar el archivo: " . $exception->getMessage() . ' ' . $file->url . ".\n";
                        }
                    }
                }
                $this->importedCount++;
            }
            $chunkTime = round(microtime(true) - $chunkStartTime, 2);
            echo "\e[0;32mChunking and importing:\e[0m {$chunkStart}-{$this->importedCount} ({$chunkTime} seconds)\n";
        });
    }
}
