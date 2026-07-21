<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'menu_item_id' => $this->menu_item_id,
            'menu_item' => new MenuItemResource($this->whenLoaded('menuItem')),
            'name' => $this->name,
            'qty' => $this->qty,
            'unit_price' => (float) $this->unit_price,
            'total' => (float) $this->total,
        ];
    }
}

