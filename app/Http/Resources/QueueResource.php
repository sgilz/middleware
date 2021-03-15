<?php
/*
 * @author    Santiago Gil Zapata sgilz@eafit.edu.co
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QueueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name"=> $this->getName(),
            "created_at"=> $this->created_at,
        ];
    }
}