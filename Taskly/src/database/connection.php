<?php

    class Connection{
        private $host = 'localhost';
        private $dbname = 'taskly';
        private $username = 'taskly_user';
        private $password = 'taskly_pass';
        private $port = '5432';

    
        public function connect(){
        try {
            $pdo = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
}
}


        


