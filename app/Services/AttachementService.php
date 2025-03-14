<?php
 namespace App\Services;

use App\Models\Attachement;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AttachementService
{
    public function storeAttachement($model, Request $attachment)
    {
        $attachment->validate([
           'file'=> 'required|mimes:mp4,jpeg,png,pdf|max:10240',
        ]);

        $file = $attachment->file('file');
        $originalName = $file->getClientOriginalName();

        if (preg_match('/\.[^.]+\./', $originalName) || strpos($originalName, '..') !== false || strpos($originalName, '/') !== false || strpos($originalName, '\\') !== false) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }

        $allowedMimeTypes = ['image/jpeg','image/png', 'video/mp4', 'application/pdf'];
        $mime_type = $file->getClientMimeType();

        if (!in_array($mime_type, $allowedMimeTypes)) {
            throw new FileException(trans('general.invalidFileType'), 403);
        }

        $fileName = Str::random(32);
        $extension = $file->getClientOriginalExtension();
        $filePath = "{$fileName}.{$extension}";

        if (!Storage::disk('public')->exists('uploads'))
        {
          Storage::disk('public')->makeDirectory('uploads');
       }
        $path = Storage::disk('uploads')->putFileAs('', $file, $fileName . '.' . $extension);
        $url = Storage::disk('uploads')->url($filePath);

        $upload = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'uploads', // Optional folder
            'resource_type' => 'auto' // Automatically detect file type
        ]);
        $url = $upload->getSecurePath();
        $attachment = $model->attachements()->create([
            'file_name' => $fileName,
            'file_path' => $url,
        ]);
        return $url; // Return the URL of the stored attachment


    }

     public function deletePhoto(Attachement $photo) {
       // Cloudinary::destroy($photo->public_id);
              Storage::delete($photo->file_path);
               $photo->delete();  }

            }


