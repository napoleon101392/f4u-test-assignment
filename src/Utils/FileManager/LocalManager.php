<?php

namespace Napoleon\Utils\FileManager;

use Exception;
use Napoleon\Utils\FileManager\Exceptions\FileException;

/**
 * This class driver for local filesystem
 */
class LocalManager implements FileManagerInterface
{
    protected $path;

    /**
     * Undocumented function
     *
     * @param [type] $path_to_file
     */
    public function __construct($path_to_file)
    {
        $this->path = $path_to_file;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function get()
    {
        try {
            if (! $data = json_decode(\file_get_contents($this->path))) {
                throw new Exception;
            }

            return $data;
        } catch (Exception $e) {
            throw new FileException($this->path);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $table
     *
     * @return void
     */
    public function add($data, $table)
    {
        $database = json_decode(\file_get_contents($this->path), true);
        $database[$table][] = array_merge([
            'id' => $this->autoIncrement($database[$table])
        ], $data);

        \file_put_contents($this->path, json_encode($database, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $records
     *
     * @return void
     */
    protected function autoIncrement($records)
    {
        $last = array_pop($records);

        return strval($last['id'] + 1);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $table
     *
     * @return void
     */
    public function delete($id, $table)
    {
        $database = json_decode(\file_get_contents($this->path), true);
        $original = count($database[$table]);

        foreach ($database[$table] as $key => $record) {
            if ($record['id'] !== $id) {
                continue;
            }

            unset($database[$table][$key]);
        }

        if (count($database[$table]) > $original) {
            return false;
        }

        $database[$table] = array_values($database[$table]);

        \file_put_contents($this->path, json_encode($database, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param [type] $table
     *
     * @return void
     */
    public function modify($data, $table)
    {
        $database = json_decode(\file_get_contents($this->path), true);

        $database[$table] = $data;

        \file_put_contents($this->path, json_encode($database, JSON_PRETTY_PRINT));

        return true;
    }
}
