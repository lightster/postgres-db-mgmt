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
     * @var array
     */
    private $options;

    /**
     * @param string $hostname
     * @param string $username
     * @param string $pg_bin
     * @param array $options
     */
    public function __construct($hostname, $username, $pg_bin, array $options = [])
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->pg_bin = $pg_bin;
        $this->options = $options;
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

    /**
     * @param string $option_name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($option_name, $default = null)
    {
        if (!array_key_exists($option_name, $this->options)) {
            return $default;
        }

        return $this->options[$option_name];
    }
}
