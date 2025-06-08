<?php

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

if ( !function_exists('upload_file') ) {
     function upload_file(File|UploadedFile $file, string $disk = 'local', string $folderStore = 'uploads', string $fileName = '')
     {
          if ( $fileName ) {
               $path = Storage::disk($disk)->putFileAs($folderStore, $file, $fileName);
               // Get Url
               $url  = Storage::disk($disk)->url($path);
          } else {
               $path = Storage::disk($disk)->put($folderStore, $file, $fileName);
               // Get Url
               $url  = Storage::disk($disk)->url($path);
          }

          return $url;
     }
}
