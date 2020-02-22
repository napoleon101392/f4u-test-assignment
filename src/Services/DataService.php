<?php

namespace Napoleon\Services;

use Napoleon\Utils\FileManager\LocalManager;

class DataService implements DataServiceInterface
{
    protected $localFile;

    /**
     *  It would be good to use adapter base implementation
     *  so that the file manager will be the one to handle drivers
     *  such as local, s3, rockspace or whatever
     *
     * @param [type] $path
     */
    public function __construct($path = __DIR__ . '/../../storage/database.json')
    {
        $this->localFile = new LocalManager($path);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function driver()
    {
        return $this->localFile;
    }
}
