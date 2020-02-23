<?php

namespace Napoleon\Models;

use Napoleon\Services\DataServiceInterface;

class Model
{
    /** Manipulator for data */
    protected $dataService;

    /**
     * Instanciate the driver for the data
     *
     * @param DataServiceInterface $dataService
     */
    public function __construct(DataServiceInterface $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * Gets all the record on the selected table
     *
     * @return mixed
     */
    public function all()
    {
        return $this->dataService->driver()->get()->{$this->table};
    }

    /**
     * Get the first record
     *
     * @return mixed
     */
    public function first()
    {
        if (!isset($this->all()[0])) {
            return [];
        }

        return $this->all()[0];
    }

    /**
     * Fetch a record on specified field or condition
     *
     * @param array $condition
     *
     * @return mixed
     */
    public function where(array $condition)
    {
        $table = $this->dataService->driver()->get()->{$this->table};

        return array_filter($table, function ($record) use ($condition) {
            foreach ($condition as $key => $value) {
                if (!isset($record->{$key})) {
                    throw new FieldNotFoundException("$key not found");
                }
                return $record->{$key} == $value;
            }
        });
    }

    /**
     * This will insert a record
     *
     * @param [type] $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return $this->dataService->driver()->add($data, $this->table);
    }

    /**
     * To delete a record
     *
     * @param [type] $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->dataService->driver()->delete($id, $this->table);
    }

    /**
     * To update a record
     *
     * @param [type] $data
     *
     * @return mixed
     */
    public function update($data)
    {
        return $this->dataService->driver()->modify($data, $this->table);
    }
}
