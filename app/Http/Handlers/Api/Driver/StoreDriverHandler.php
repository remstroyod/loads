<?php
namespace App\Http\Handlers\Api\Driver;

use App\Http\Handlers\Api\BaseHandler;
use App\Models\Driver;
use App\Models\Language;
use App\Traits\FileTrait;
use Illuminate\Http\Request;

class StoreDriverHandler extends BaseHandler
{

    use FileTrait;

    public function process(Request $request, Driver $driver = null)
    {
        try {

            if($request->has('languages'))
            {
                $lang = Language::whereIn('code', $request->get('languages'))->pluck('id')->toArray();
                $request->merge(['languages' => $lang]);
            }

            if ($driver) {
                $driver->update($request->all());
            } else {
                $driver = $request->user()->drivers()->create($request->all());
            }

            if($request->has('languages'))
            {
                $driver->languages()->sync($request->get('languages'));
            }

            if($request->has('file'))
            {

                $file = $this->fileUpload($request, 'file');

                if($file)
                {
                    $driver->files()->delete();
                    $driver->files()->create([
                        'type'                  => $request->get('file_type'),
                        'number'                => $request->get('file_number'),
                        'valid_from'            => $request->get('file_valid_from'),
                        'valid_until'           => $request->get('file_valid_until'),
                        'extension'             => $file['extension'],
                        'path'                  => $file['path'],
                        'name'                  => $file['name'],
                        'original_name'         => $file['original_name'],
                    ]);
                }
            }

            return $driver;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

}
