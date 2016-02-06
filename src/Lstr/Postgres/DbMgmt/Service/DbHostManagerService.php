<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Exception;
use Lstr\Postgres\DbMgmt\Model\Host;

class DbHostManagerService
{
    /**
     * @var array
     */
    private $host_config;

    /**
     * @var array
     */
    private $hosts = [];

    /**
     * @param array $host_config
     */
    public function __construct(array $host_config)
    {
        $this->host_config = $host_config;
    }

    /**
     * @param $host_key
     * @return Host
     * @throws Exception
     */
    public function getHost($host_key)
    {
        $this->lazyLoadHost($host_key);

        if (empty($this->hosts[$host_key])) {
            throw new Exception("Host with key '{$host_key}' not found.");
        }

        return $this->hosts[$host_key];
    }

    /**
     * @param $host_key
     * @return Host
     */
    private function lazyLoadHost($host_key)
    {
        if (array_key_exists($host_key, $this->hosts)) {
            return $this->hosts[$host_key];
        }

        if (empty($this->host_config[$host_key])) {
            $this->hosts[$host_key] = null;
            return $this->hosts[$host_key];
        }

        $host_config = $this->host_config[$host_key];
        $this->hosts[$host_key] = new Host(
            $host_config['hostname'],
            $host_config['username'],
            $host_config['password'],
            $host_config['pg_bin']
        );

        return $this->hosts[$host_key];
    }
}
