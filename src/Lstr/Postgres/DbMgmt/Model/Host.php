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
    private $password;

    /**
     * @var string
     */
    private $pg_bin;

    /**
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $pg_bin
     */
    public function __construct($hostname, $username, $password, $pg_bin)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->pg_bin = $pg_bin;
    }
}
