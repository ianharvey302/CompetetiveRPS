<?php
class Database {
    private $dbConnector;

    /**
     * Reads configuration from the Config class above
     */
    public function __construct() {
        $host = Config::$db["host"];
        $user = Config::$db["user"];
        $database = Config::$db["database"];
        $password = Config::$db["pass"];
        $port = Config::$db["port"];

        $this->dbConnector = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");
        
        $sqlList = ['CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username TEXT NOT NULL UNIQUE,
            password TEXT,
            numWin INTEGER DEFAULT 0,
            numTie INTEGER DEFAULT 0,
            numLoss INTEGER DEFAULT 0,
            numRock INTEGER DEFAULT 0,
            numRockWin INTEGER DEFAULT 0,
            numRockTie INTEGER DEFAULT 0,
            numRockLoss INTEGER DEFAULT 0,
            numPaper INTEGER DEFAULT 0,
            numPaperWin INTEGER DEFAULT 0,
            numPaperTie INTEGER DEFAULT 0,
            numPaperLoss INTEGER DEFAULT 0,
            numScissors INTEGER DEFAULT 0,
            numScissorsWin INTEGER DEFAULT 0,
            numScissorsTie INTEGER DEFAULT 0,
            numScissorsLoss INTEGER DEFAULT 0
            );'];
        
        foreach ($sqlList as $query) {
            pg_query($this->dbConnector, $query);
        }
    }

    public function query($query, ...$params) {
        // Use safe querying
        $res = pg_query_params($this->dbConnector, $query, $params);

        // If there was an error, print it out
        if ($res === false) {
            echo pg_last_error($this->dbConnector);
            return false;
        }

        // Return an array of associative arrays (the rows
        // in the database)
        return pg_fetch_all($res);
    }
}