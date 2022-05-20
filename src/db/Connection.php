<?php

class Connection {

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $pass;

    /**
     * @var string
     */
    private string $db;

    /**
     * Connection constructor.
     * @param $user
     * @param $host
     * @param $pass
     * @param $db
     */
    public function __construct($user, $host, $pass, $db)
    {
        $this->user = $user;
        $this->host = $host;
        $this->pass = $pass;
        $this->db = $db;
    }

    /**
     * @return mysqli
     */
    public function connect(): mysqli
    {
        return new mysqli($this->host, $this->user, $this->pass, $this->db);

    }

}