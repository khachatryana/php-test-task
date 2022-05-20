<?php

class Log
{
    /**
     * @var string
     */
    private string $log;

    /**
     * @var string
     */
    private string $dir;



    /**
     * @param string $log
     */
    public function setLog(string $log): void
    {
        $this->log = $log;
    }

    /**
     * @param string $dir
     */
    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    public function createLog(): void
    {

        $reportFile = fopen($this->dir, "w");
        fwrite($reportFile, $this->log);
        fclose($reportFile);
    }
}