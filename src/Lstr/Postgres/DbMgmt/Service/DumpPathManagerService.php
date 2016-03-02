<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Exception;
use Lstr\Postgres\DbMgmt\Model\Host;

class DumpPathManagerService
{
    /**
     * @var array
     */
    private $source_paths;

    /**
     * @var array
     */
    private $destination_paths;

    /**
     * @param array $source_paths
     * @param array $destination_paths
     */
    public function __construct(array $source_paths, array $destination_paths)
    {
        $this->source_paths = $source_paths;
        $this->destination_paths = $destination_paths;
    }

    /**
     * @param string $destination_path
     */
    public function checkDestinationPath($destination_path)
    {
        $this->checkPath($this->destination_paths, $destination_path);
    }

    /**
     * @param string $source_path
     */
    public function checkSourcePath($source_path)
    {
        $this->checkPath($this->source_paths, $source_path);
    }

    /**
     * @param array $valid_paths
     * @param string $path
     */
    private function checkPath(array $valid_paths, $path)
    {
    }
}
