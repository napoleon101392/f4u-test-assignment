<?php

namespace Napoleon\Models;

use Napoleon\Services\DataServiceInterface;

class Model
{
    /**  */
    protected $dataService;

    /**
     * Undocumented function
     *
     * @param DataServiceInterface $dataService
     */
    public function __construct(DataServiceInterface $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function all()
    {
        return $this->dataService->driver()->get()->{$this->table};
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function first()
    {
        if (!isset($this->all()[0])) {
            return [];
        }

        return $this->all()[0];
    }

    /**
     * Undocumented function
     *
     * @param array $condition
     *
     * @return void
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
     * Undocumented function
     *
     * @param [type] $data
     *
     * @return void
     */
    public function create($data)
    {
        return $this->dataService->driver()->add($data, $this->table);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     *
     * @return void
     */
    public function delete($id)
    {
        return $this->dataService->driver()->delete($id, $this->table);
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     *
     * @return void
     */
    public function update($data)
    {
        return $this->dataService->driver()->modify($data, $this->table);
    }
}
