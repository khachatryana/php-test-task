<?php

class Constant
{
    /**
     * @var string
     */
    private string $user = 'root';

    /**
     * @var string
     */
    private string $host = 'localhost';

    /**
     * @var string
     */
    private string $pass = 'dev123';

    /**
     * @var string
     */
    private string $db = 'test_taska';


    /**
     * @var string
     */
    private string $OODLE_SITECODE = 'oodle';

    /**
     * @var int
     */
    private int $exampleUrlCount = 3;

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getDb(): string
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getOODLESITECODE(): string
    {
        return $this->OODLE_SITECODE;
    }

    /**
     * @return int
     */
    public function getExampleUrlCount(): int
    {
        return $this->exampleUrlCount;
    }
}