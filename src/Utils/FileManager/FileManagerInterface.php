<?php

namespace Napoleon\Utils\FileManager;

interface FileManagerInterface
{
    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function get();

    /**
     * Undocumented function
     *
     * @param array $data
     * @param string $table
     *
     * @return mixed
     */
    public function add($data, $entity);

    /**
     * Undocumented function
     *
     * @param string $id
     * @param string $entity
     *
     * @return mixed
     */
    public function delete($id, $entity);

    /**
     * Undocumented function
     *
     * @param array $data
     * @param string $entity
     *
     * @return mixed
     */
    public function modify($data, $entity);
}
