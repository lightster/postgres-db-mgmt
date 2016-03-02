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
     * @var DumpPathManagerService
     */
    private $dump_path_manager;

    /**
     * @param DbHostManagerService $db_host_manager
     */
    public function __construct(DbHostManagerService $db_host_manager, DumpPathManagerService $dump_path_manager)
    {
        $this->db_host_manager = $db_host_manager;
        $this->dump_path_manager = $dump_path_manager;
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

        $this->dump_path_manager->checkDestinationPath($dest_path);

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
            $return['command'] = $command;
        }

        return $return;
    }

    /**
     * @param string $host_key
     * @param string $database
     * @param string $source_path
     * @return array
     */
    public function restore($host_key, $database, $source_path)
    {
        $host = $this->db_host_manager->getHost($host_key);

        $this->dump_path_manager->checkDestinationPath($source_path);

        $create_response = $this->create($host, $database);
        if ($create_response['exitCode']) {
            return $create_response;
        }

        $command = $host->getPathToPgBin('pg_restore')
            . " -U " . escapeshellarg($host->getUsername())
            . " -h " . escapeshellarg($host->getHostname())
            . " -F c -d " . escapeshellarg($database)
            . " -j " . escapeshellarg($host->getOption('import_processes', 1))
            . " -v " . escapeshellarg($source_path);

        $process = new Process($command);
        $process->setTimeout(60 * 60 * 6); // 6 hours
        $process->setIdleTimeout(60 * 30); // 30 minutes

        $process->run();

        $return = [
            'exitCode' => $process->getExitCode(),
        ];

        if ($return['exitCode']) {
            $return['stderr'] = $process->getErrorOutput();
            $return['command'] = $command;
        }

        return $return;
    }

    /**
     * @param $host
     * @param $database
     * @return array
     */
    private function create($host, $database)
    {
        $command = $host->getPathToPgBin('createdb')
            . " -U " . escapeshellarg($host->getUsername())
            . " -h " . escapeshellarg($host->getHostname())
            . " " . escapeshellarg($database);

        $process = new Process($command);
        $process->setTimeout(60); // 1 minute
        $process->setIdleTimeout(60); // 1 minute

        $process->run();

        $return = [
            'exitCode' => $process->getExitCode(),
        ];

        if ($return['exitCode']) {
            $return['stderr'] = $process->getErrorOutput();
            $return['command'] = $command;
        }

        return $return;
    }
}
