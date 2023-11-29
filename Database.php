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
            );',
            "INSERT INTO users (username) VALUES ('Guest') ON CONFLICT (username) DO NOTHING;",
            'CREATE TABLE IF NOT EXISTS matchmaking (
            id SERIAL PRIMARY KEY,
            username TEXT NOT NULL
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

    public function enqueuePlayer($username) {
        $res = $this->query("INSERT INTO matchmaking (username) VALUES ($1) RETURNING id;", $username);
        if (!$res) return false;
        return $res[0]["id"];
    }

    public function dequeuePlayer($primaryKey) {
        $this->query("DELETE FROM matchmaking WHERE id = ($1);", $primaryKey);
    }

    public function createMoveTable($id, $move) {
        $tableName = "move_" . $id;
        $tableQuery = "CREATE TABLE IF NOT EXISTS $tableName (move TEXT PRIMARY KEY)";
    
        // Insert data query
        $insertQuery = "INSERT INTO $tableName(move) VALUES (' " . $move . " ')";
        
        // Execute the queries
        $tableRes = $this->query($tableQuery);
        $insertRes = $this->query($insertQuery);
    
        // Check for errors
        if (!$tableRes) return "Failed to create table";
        if (!$insertRes) return "Failed to insert move";
    
        return true;
    }
    

    public function getMoveEntry($id) {
        $tableName = "move_" . $id;
    
        // Check if the table exists
        $tableExists = $this->tableExists($tableName);
        if (!$tableExists) {
            return false;
        }
    
        // If the table exists, proceed with fetching the move entry
        $getRes = $this->query("SELECT * FROM $tableName;");
        if (!$getRes) {
            return false;
        }
    
        // Drop the table after fetching the move entry
        $this->query("DROP TABLE IF EXISTS $tableName;");
    
        return $getRes[0]["move"];
    }
    
    // Helper function to check if a table exists
    private function tableExists($tableName) {
        $checkRes = $this->query("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = $1);", $tableName);
        return $checkRes[0]['exists'] === 't';
    }
    

    public function deleteMoveTable($id) {
        $tableName = "move_" . $id;
        $this->query("DROP TABLE IF EXISTS ($1)", $tableName);
    }

    public function getFirstAvailablePlayer($id) {
        $res = $this->query('SELECT *
        FROM matchmaking
        WHERE id <> ($1)
        ORDER BY id
        LIMIT 1;', $id);
        if (empty($res)) return false;
        return $res[0];
    }

    public function rockWin($username) {
        $this->query("UPDATE users SET 
        numWin = numWin + 1,
        numRock = numRock + 1, 
        numRockWin = numRockWin + 1 WHERE username = ($1);", $username);
    }

    public function rockTie($username) {
        $this->query("UPDATE users SET 
        numTie = numTie + 1,
        numRock = numRock + 1, 
        numRockTie = numRockTie + 1 WHERE username = ($1);", $username);
    }

    public function rockLoss($username) {
        $this->query("UPDATE users SET 
        numLoss = numLoss + 1,
        numRock = numRock + 1, 
        numRockLoss = numRockLoss + 1 WHERE username = ($1);", $username);
    }

    public function paperWin($username) {
        $this->query("UPDATE users SET 
        numWin = numWin + 1,
        numPaper = numPaper + 1, 
        numPaperWin = numPaperWin + 1 WHERE username = ($1);", $username);
    }

    public function paperTie($username) {
        $this->query("UPDATE users SET 
        numTie = numTie + 1,
        numPaper = numPaper + 1, 
        numPaperTie = numPaperTie + 1 WHERE username = ($1);", $username);
    }

    public function paperLoss($username) {
        $this->query("UPDATE users SET 
        numLoss = numLoss + 1,
        numPaper = numPaper + 1, 
        numPaperLoss = numPaperLoss + 1 WHERE username = ($1);", $username);
    }

    public function scissorsWin($username) {
        $this->query("UPDATE users SET 
        numWin = numWin + 1,
        numScissors = numScissors + 1, 
        numScissorsWin = numScissorsWin + 1 WHERE username = ($1);", $username);
    }

    public function scissorsTie($username) {
        $this->query("UPDATE users SET 
        numTie = numTie + 1,
        numScissors = numScissors + 1, 
        numScissorsTie = numScissorsTie + 1 WHERE username = ($1);", $username);
    }

    public function scissorsLoss($username) {
        $this->query("UPDATE users SET 
        numLoss = numLoss + 1,
        numScissors = numScissors + 1, 
        numScissorsLoss = numScissorsLoss + 1 WHERE username = ($1);", $username);
    }

    public function loseByDefault($username) {
        $this->query("UPDATE users SET
        numLoss = numLoss + 1 WHERE username = ($1)", $username);
    }

    public function winByDefault($username) {
        $this->query("UPDATE users SET
        numWin = numWin + 1 WHERE username = ($1)", $username);
    }
}