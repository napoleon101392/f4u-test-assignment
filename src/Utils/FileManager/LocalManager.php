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
     * @param string $path_to_file
     */
    public function __construct($path_to_file)
    {
        $this->path = $path_to_file;
    }

    /**
     * Reads the file
     *
     * @return mixed
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
     * Override the file
     *
     * @param array $data
     * @param string $table
     *
     * @return boolean
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
     * It increment the id by the last record id
     *
     * @param array $records
     *
     * @return string
     */
    protected function autoIncrement($records)
    {
        $last = array_pop($records);

        return strval($last['id'] + 1);
    }

    /**
     * Deletes a record in the file
     *
     * @param string $id
     * @param string $table
     *
     * @return boolean
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
     * Updates a record
     *
     * @param string $data
     * @param string $table
     *
     * @return mixed
     */
    public function modify($data, $table)
    {
        $database = json_decode(\file_get_contents($this->path), true);

        $database[$table] = $data;

        \file_put_contents($this->path, json_encode($database, JSON_PRETTY_PRINT));

        return true;
    }
}
