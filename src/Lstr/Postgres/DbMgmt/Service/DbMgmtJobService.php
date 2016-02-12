<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;

class DbMgmtJobService
{
    /**
     * @var JobQueue
     */
    private $job_queue;

    /**
     * @var DbMgmtProcessService
     */
    private $process_service;

    /**
     * @param DbMgmtProcessService $process_service
     * @param JobQueue $job_queue
     */
    public function __construct(DbMgmtProcessService $process_service, JobQueue $job_queue)
    {
        $this->process_service = $process_service;
        $this->job_queue = $job_queue;
    }

    /**
     * @param string $host_key
     * @param string $database
     * @param string $dest_path
     * @param string $callback_url
     */
    public function queueDump($host_key, $database, $dest_path, $callback_url = null)
    {
        $this->job_queue->push(
            'postgres.db-mgmt-job.runDump',
            [
                'host_key'         => $host_key,
                'database'         => $database,
                'destination_path' => $dest_path,
                'callback_url'     => $callback_url,
            ]
        );
    }

    /**
     * @param array $job_params
     */
    public function runDump(array $job_params)
    {
        $result = $this->process_service->dump(
            $job_params['host_key'],
            $job_params['database'],
            $job_params['destination_path']
        );

        $this->queueHttpPostRequest($job_params['callback_url'], $result);
    }

    /**
     * @param string $callback_url
     * @param array $callback_params
     */
    private function queueHttpPostRequest($callback_url, array $callback_params)
    {
        if (!$callback_url) {
            return;
        }

        $this->job_queue->push(
            'postgres.db-mgmt-job.runHttpPostRequest',
            [
                'callback_url'    => $callback_url,
                'callback_params' => $callback_params,
            ]
        );
    }
}
