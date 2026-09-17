<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryDetailCollection extends ResourceCollection
{
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200,
        ];
    }
}
