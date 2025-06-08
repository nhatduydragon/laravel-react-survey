<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUploadFileToS3;
use App\Models\Document;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AwsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Hello from AWS',
        ]);
    }

    public function upload(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $file       = $request->file('upload');
            $fileName   = $request->file('upload')->getClientOriginalName();
            $sizeInByte = $file->getSize();
            $sizeInMB   = $sizeInByte / 1024 / 1024;
            $fileName   = time() . '_' . $fileName;

            if ( $sizeInMB > 2) {
                $url  = upload_file($file, 'public', 'uploads', $fileName);
                
                $document = Document::create([
                    'file_name' => $fileName,
                    'file_path' => storage_path('app/public/uploads/' . basename($url)),
                    'status'    => Document::UPLOAD_LOCAL,
                ]);

                ProcessUploadFileToS3::dispatch($document);

                return response()->json([
                    'message' => 'File upload too large: it seraval minutes',
                ]);
            }
            $url  = upload_file($file, 's3', 'uploads', $fileName);

            $document = Document::create([
                'file_name' => $fileName,
                'file_path' => $url,
                'status'    => Document::UPLOAD_S3,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'data'    => $document,
            ]);
        }

        return response()->json([
            'data'  => 'No store',
        ]);
    }
}
