<?php
/*
 * @author    Santiago Gil Zapata sgilz@eafit.edu.co
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "body"=> $this->getBody(),
            "date"=> $this->getDate(),
        ];
    }
}