<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;

use Symfony\Component\Process\Process;

class DbMgmtProcessService
{
    /**
     * @var DbHostManagerService
     */
    private $db_host_manager;

    /**
     * @param DbHostManagerService $db_host_manager
     */
    public function __construct(DbHostManagerService $db_host_manager)
    {
        $this->db_host_manager = $db_host_manager;
    }

    /**
     * @param string $host_key
     * @param string $database
     * @param string $dest_path
     * @return array
     */
    public function dump($host_key, $database, $dest_path)
    {
        $host = $this->db_host_manager->getHost($host_key);

        $command = $host->getPathToPgBin('pg_dump')
            . " -U " . escapeshellarg($host->getUsername())
            . " -h " . escapeshellarg($host->getHostname())
            . " -F c " . escapeshellarg($database)
            . " -v > " . escapeshellarg($dest_path);

        $process = new Process($command);
        $process->setTimeout(60 * 60 * 6); // 6 hours
        $process->setIdleTimeout(60 * 30); // 30 minutes

        $process->run();

        $return = [
            'exitCode' => $process->getExitCode(),
            'fileSize' => filesize($dest_path),
        ];

        if ($return['exitCode']) {
            $return['stderr'] = $process->getErrorOutput();
        }

        return $return;
    }
}
