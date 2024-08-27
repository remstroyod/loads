<?php

namespace App\Traits;

use App\Enums\FileEnum;
use Exception;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileTrait
{

    public function fileUpload(Request $request, string $filename = null): array
    {

        try {

            $this->token();

            $disk = Storage::disk('google');
            $fileNameOriginal = $request->file('file')->getClientOriginalName();
            $fileExtension = $request->file('file')->getClientOriginalExtension();

            $file = $disk->put($request->ip(), $request->file('file'), 'public');

            if($file)
            {

                $slice = Str::between($disk->url($file), 'https://drive.google.com/uc?id=', '&export=media');
                return [
                    'type'          => FileEnum::getValueFromName($filename),
                    'original_name' => $fileNameOriginal,
                    'path'          => sprintf('https://drive.google.com/file/d/%s/preview', $slice),
                    'extension'     => $fileExtension,
                    'name'          => $file,
                ];
            }
            return [];
        } catch (Exception $e) {
            Log::debug(sprintf('File - %s Line - %s | Error File Upload: %s', __FILE__, __LINE__, $e->getMessage()));
        }

        return [];

    }

    private function token()
    {

        try {

            $client = new Google_Client();

            $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));

            $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

            $newRefreshToken = $client->getRefreshToken();

            if ($newRefreshToken) {
                putenv('GOOGLE_DRIVE_REFRESH_TOKEN='.$newRefreshToken);
            }

        } catch (Exception $e) {
            Log::debug(sprintf('File - %s Line - %s | Error File Upload Refresh Token: %s', __FILE__, __LINE__, $e->getMessage()));
        }
    }

}
