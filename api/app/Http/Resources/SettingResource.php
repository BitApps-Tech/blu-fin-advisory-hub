<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Convert value based on type (PHP 7.4 compatible)
        switch ($this->type) {
            case 'boolean':
                $value = (bool) $this->value;
                break;
            case 'json':
                $value = json_decode($this->value, true);
                break;
            default:
                $value = $this->value;
                break;
        }

        return [
            'id' => $this->id,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $value,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
        ];
    }
}

