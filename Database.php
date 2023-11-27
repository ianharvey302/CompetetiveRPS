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

    public function getUserStats($username) {
        $res = $this->query("select * from users where username = $1;", $username);
        $numWin = $res[0]["numwin"];
        $numTie = $res[0]["numtie"];
        $numLoss = $res[0]["numloss"];
        $numRock = $res[0]["numrock"];
        $numRockWin = $res[0]["numrockwin"];
        $numRockTie = $res[0]["numrocktie"];
        $numRockLoss = $res[0]["numrockloss"];
        $numPaper = $res[0]["numpaper"];
        $numPaperWin = $res[0]["numpaperwin"];
        $numPaperTie = $res[0]["numpapertie"];
        $numPaperLoss = $res[0]["numpaperloss"];
        $numScissors = $res[0]["numscissors"];
        $numScissorsWin = $res[0]["numscissorswin"];
        $numScissorsTie = $res[0]["numscissorstie"];
        $numScissorsLoss = $res[0]["numscissorsloss"];

        return array('username' => $username, 'numWin' => $numWin, 'numLoss' =>$numLoss, 'numTie' => $numTie, 'numRock' => $numRock, 
            'numRockWin' => $numRockWin, 'numRockLoss' => $numRockLoss, 'numRockTie' => $numRockTie, 'numPaper' => $numPaper, 'numPaperWin' => $numPaperWin, 
            'numPaperLoss' => $numPaperLoss, 'numPaperTie' => $numPaperTie, 'numScissors' => $numScissors, 'numScissorsWin' => $numScissorsWin, 
            'numScissorsLoss' => $numScissorsLoss, 'numScissorsTie' => $numScissorsTie);
    }

    public function getGlobalStats() {
        $res = $this->query("SELECT
        SUM(numwin) as sum_numwin,
        SUM(numtie) as sum_numtie,
        SUM(numloss) as sum_numloss,
        SUM(numrock) as sum_numrock,
        SUM(numrockwin) as sum_numrockwin,
        SUM(numrocktie) as sum_numrocktie,
        SUM(numrockloss) as sum_numrockloss,
        SUM(numpaper) as sum_numpaper,
        SUM(numpaperwin) as sum_numpaperwin,
        SUM(numpapertie) as sum_numpapertie,
        SUM(numpaperloss) as sum_numpaperloss,
        SUM(numscissors) as sum_numscissors,
        SUM(numscissorswin) as sum_numscissorswin,
        SUM(numscissorstie) as sum_numscissorstie,
        SUM(numscissorsloss) as sum_numscissorsloss
        FROM users;");
        $numWin = $res[0]["sum_numwin"];
        $numTie = $res[0]["sum_numtie"];
        $numLoss = $res[0]["sum_numloss"];
        $numRock = $res[0]["sum_numrock"];
        $numRockWin = $res[0]["sum_numrockwin"];
        $numRockTie = $res[0]["sum_numrocktie"];
        $numRockLoss = $res[0]["sum_numrockloss"];
        $numPaper = $res[0]["sum_numpaper"];
        $numPaperWin = $res[0]["sum_numpaperwin"];
        $numPaperTie = $res[0]["sum_numpapertie"];
        $numPaperLoss = $res[0]["sum_numpaperloss"];
        $numScissors = $res[0]["sum_numscissors"];
        $numScissorsWin = $res[0]["sum_numscissorswin"];
        $numScissorsTie = $res[0]["sum_numscissorstie"];
        $numScissorsLoss = $res[0]["sum_numscissorsloss"];

        return array('numWin' => $numWin, 'numLoss' =>$numLoss, 'numTie' => $numTie, 'numRock' => $numRock, 
        'numRockWin' => $numRockWin, 'numRockLoss' => $numRockLoss, 'numRockTie' => $numRockTie, 'numPaper' => $numPaper, 'numPaperWin' => $numPaperWin, 
        'numPaperLoss' => $numPaperLoss, 'numPaperTie' => $numPaperTie, 'numScissors' => $numScissors, 'numScissorsWin' => $numScissorsWin, 
        'numScissorsLoss' => $numScissorsLoss, 'numScissorsTie' => $numScissorsTie);
    }
}