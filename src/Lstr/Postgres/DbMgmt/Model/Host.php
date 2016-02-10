<?php

namespace Lstr\Postgres\DbMgmt\Model;

class Host
{
    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $pg_bin;

    /**
     * @param string $hostname
     * @param string $username
     * @param string $pg_bin
     */
    public function __construct($hostname, $username, $pg_bin)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->pg_bin = $pg_bin;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPathToPgBin($bin)
    {
        return rtrim($this->pg_bin, '\/\\') . '/' . $bin;
    }
}
