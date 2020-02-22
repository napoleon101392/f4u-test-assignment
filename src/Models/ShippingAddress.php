<?php

namespace Napoleon\Models;

class ShippingAddress extends Model
{
    protected $table = 'shipping_addresses';

    /**
     * Find address by client ID
     *
     * @param [type] $id
     *
     * @return void
     */
    public function ofClient($id)
    {
        return parent::where(['client_id' => $id]);
    }
}
