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
     * @param string $path
     */
    public function __construct($path = __DIR__ . '/../../storage/database.json')
    {
        $this->localFile = new LocalManager($path);
    }

    /**
     * Instance of the driver
     *
     * @return LocalManager
     */
    public function driver()
    {
        return $this->localFile;
    }
}
