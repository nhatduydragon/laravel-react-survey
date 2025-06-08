<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessUploadFileToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Document $document)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = $this->document;

        try {
            if ( file_exists($document->file_path) ) {
                $file     = new File($document->file_path);
                $fileName = $file->getFilename();
                $url      = upload_file($file, 's3', 'uploads', $fileName);

                $document->update([
                    'file_path' => $url,
                    'status'    => Document::UPLOAD_S3,
                ]);

            } else {
                log('Document ID - ' . $document->id . ' : File not Exists');
            }
        } catch (\Exception $e) {
            info("File: " . $e->getFile() . " - Line: " . $e->getLine() . " - Message: " . $e->getMessage());
        }
    }
}
