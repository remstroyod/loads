<?php
namespace App\Http\Handlers\Api\Order;

use App\Http\Handlers\Api\BaseHandler;
use App\Models\Order;
use App\Traits\FileTrait;
use Illuminate\Http\Request;

class StoreFilesOrderHandler extends BaseHandler
{

    use FileTrait;

    public function process(Request $request, Order $order)
    {
        try {

            if($request->has('file'))
            {

                $file = $this->fileUpload($request, 'file');

                if($file)
                {
                    $file = $order->documents()->create([
                        'type'                  => $request->get('type'),
                        'extension'             => $file['extension'],
                        'path'                  => $file['path'],
                        'name'                  => $file['name'],
                        'original_name'         => $file['original_name'],
                    ]);
                }
            }

            return $file;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

}
