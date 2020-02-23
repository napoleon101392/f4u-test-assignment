<?php

namespace Napoleon\Repositories;

use Napoleon\Models\Client;
use Napoleon\Services\DataService;
use Napoleon\Models\ShippingAddress;
use Napoleon\Repositories\Exceptions\NotFoundException;
use Napoleon\Repositories\Exceptions\MaximumAddressCountException;
use Napoleon\Repositories\Exceptions\DefaultAddressNotRemovableException;

class ClientRepository
{
    /**
     * Undocumented function
     *
     * @param DataService $service
     */
    public function __construct(DataService $service)
    {
        $this->clientModel = new Client($service);

        $this->shippingAddressModel = new ShippingAddress($service);
    }

    /**
     * Gets all the clients
     *
     * @return mixed
     */
    public function getClients()
    {
        return $this->clientModel->all();
    }

    /**
     * Gets all the Shipping addresses
     * created on all clients
     *
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->shippingAddressModel->all();
    }

    /**
     * Gets the address of a client selected
     *
     * @param string $clientId
     *
     * @return mixed
     */
    public function getAddressesByClient($clientId)
    {
        return $this->shippingAddressModel->ofClient($clientId);
    }

    /**
     * Adding shipping address to specified client
     *
     * @param array $data
     *
     * @return mixed
     */
    public function addShippingAddress(array $data)
    {
        // Validate client ID if exist
        $clientId = $data['client_id'];
        if (empty($this->clientModel->where(['id' => $clientId]))) {
            throw new NotFoundException;
        }

        $addressess = array_filter($this->shippingAddressModel->all(), function ($record) use ($clientId) {
            return $record->client_id == $clientId;
        });

        $data['is_default'] = false;

        if (count($addressess) === 0) {
            $data['is_default'] = true;
        }

        if (count($addressess) >= 3) {
            throw new MaximumAddressCountException;
        }

        return $this->shippingAddressModel->create($data);
    }

    /**
     * Removing the specified address for specified client
     *
     * @param string $clientId
     * @param string $addressId
     *
     * @return mixed
     */
    public function removeAddress($clientId, $addressId)
    {
        $filtered = array_filter($this->shippingAddressModel->all(), function($record) use ($clientId, $addressId) {
            return $record->id == $addressId && $record->client_id == $clientId;
        });

        array_map(function ($record) {
            if ($record->is_default) {
                throw new DefaultAddressNotRemovableException('Record not removable ' . json_encode($record));
            }
        }, $filtered);

        return $this->shippingAddressModel->delete($addressId);
    }

    /**
     * Update an address
     *
     * @param string $addressId
     * @param array $data
     *
     * @return mixed
     */
    public function updateAddress($addressId, array $data)
    {
        $modified = array_map(function ($record) use ($addressId, $data) {
            if ($record->id == $addressId) {
                $record->country = $data['country'];
                $record->city = $data['city'];
                $record->zipcode = $data['zipcode'];
                $record->street = $data['street'];
            }

            return $record;
        }, $this->shippingAddressModel->all());


        return $this->shippingAddressModel->update($modified);
    }
}
