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
     * @param string $proposed_path
     */
    private function checkPath(array $valid_paths, $proposed_path)
    {
        $canonical_proposed_path = realpath($proposed_path);

        foreach ($valid_paths as $valid_path) {
            if (strpos($canonical_proposed_path, realpath($valid_path)) === 0) {
                return;
            }
        }

        throw new Exception("'{$canonical_proposed_path}' is not in  the list of valid paths.");
    }
}
