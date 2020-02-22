<?php

namespace Napoleon\Utils\FileManager;

interface FileManagerInterface
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function get();

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $table
     *
     * @return void
     */
    public function add($data, $entity);

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $entity
     *
     * @return void
     */
    public function delete($id, $entity);

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $entity
     *
     * @return void
     */
    public function modify($data, $entity);
}
