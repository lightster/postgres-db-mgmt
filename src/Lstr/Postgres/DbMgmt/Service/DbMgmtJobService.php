<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;

class DbMgmtJobService
{
    /**
     * @var DbHostManagerService
     */
    private $db_host_manager;

    /**
     * @var JobQueue
     */
    private $job_queue;

    /**
     * @param object $db_host_manager
     */
    public function __construct(DbHostManagerService $db_host_manager, JobQueue $job_queue)
    {
        $this->db_host_manager = $db_host_manager;
        $this->job_queue = $job_queue;
    }

    /**
     * @param string $host_key
     * @param string $database
     * @param string $dest_path
     * @param string $callback_url
     */
    public function dump($host_key, $database, $dest_path, $callback_url = null)
    {
        $this->job_queue->push(
            'postgres.db-mgmt-sync.dump',
            [
                'host_key'         => $host_key,
                'database'         => $database,
                'destination_path' => $dest_path,
                'callback_url'     => $callback_url,
            ]
        );
    }
}
