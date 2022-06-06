<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database {

    private $host = 'db';
    private $user = 'root';
    private $password = 'password';
    private $db = 'loginProject';

    /**
     * Creates a simple database-connection.
     *
     * @return PDO
     */
    private function create_connection() {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    private function check_if_table_exist($connection, $table) {
        try {
            $connection->query("SELECT 1 FROM $table");
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * Create person Table
     * ---
     * Checks if "person" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE person:
     * - personID   => PK
     * - surname
     * - firstname
     * - nickname
     * - password
     * - birthdate 
     * - registerDate 
     * - function   => FK
     * - status     => FK
     * - occupation => FK
     * - team       => FK
     */
    private function create_person_table() {
        // here: create table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, 'person')) {
                // sql to create table
                $sql = "CREATE TABLE person(
                        personID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        surname VARCHAR(40) NOT NULL,
                        firstname VARCHAR(40) NOT NULL,
                        nickname VARCHAR(40),
                        email VARCHAR(60) NOT NULL,
                        birthDate DATE,
                        registerDate TIMESTAMP,
                        participantFunctionID int(11) UNSIGNED,
                        statusID int(11) UNSIGNED,
                        preoccupationID int(11) UNSIGNED
                        -- teamID int
                        )";

                // use exec() because no results are returned
                $conn->exec($sql);

                // foreign keys:
                $sql = "ALTER TABLE person
                        ADD FOREIGN KEY (participantFunctionID) REFERENCES participantFunction(functionID),
                        ADD FOREIGN KEY (statusID) REFERENCES status(statusID),
                        ADD FOREIGN KEY (preoccupationID) REFERENCES preoccupation(preoccupationID)
                        -- ADD FOREIGN KEY (team) REFERENCES team(teamID)
                        ";

                $conn->exec($sql);

                echo "user person table created successfully";
            }
        } 
        catch (PDOException $e) {
            echo $e->getMessage();
            echo "\r\n";
        }
        $conn = null;
    }

    /**
     * TABLE function:
     * - functionID => PK
     * - functionName
     */
    private function create_participantFunction_table() {
        try {
            $conn = $this->create_connection();
            if(!$this->check_if_table_exist($conn, "participantFunction")) {
                $sql = "CREATE TABLE participantFunction (
                        functionID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        functionName VARCHAR(40))";

                $conn->exec($sql);
            }
        }
        catch (PDOExcpetion $e) {
            echo $e->getMessage();
            echo "\r\n";
        }
        $conn = null;
    }
    /**
     * TABLE status:
     * - statusID 
     * - statusName 
     */
    private function create_status_table() {
        try {
            $conn = $this->create_connection();
            if(!$this->check_if_table_exist($conn, "status")) {
                $sql = "CREATE TABLE status (
                        statusID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                        statusName VARCHAR(40))";
                
                $conn->exec($sql);
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            echo "\r\n";
        }
        $conn = null;
    }

    /**
     * TABLE preoccupation
     * - preoccupationID    => PK
     * - preoccupationName
     * - placeOfWork        => FK
     */
    private function create_preoccupation_table() {
        try {
            $conn = $this->create_connection();
            if(!$this->check_if_table_exist($conn, "preoccupation")) {
                $sql = "CREATE TABLE preoccupation (
                        preoccupationID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        preoccupationName VARCHAR(40),
                        placeOfWorkID int(11) UNSIGNED
                    )";

                $conn ->exec($sql);

                $sql = "ALTER TABLE preoccupation
                        ADD FOREIGN KEY (placeOfWorkID) REFERENCES placeOfWork(placeOfWorkID)
                    ";

                $conn->exec($sql);
                echo "preoccupation table was created successfully";
            }
        }
        catch (PDOException $e){
            echo $e->getMessage();
            echo "\r\n";
        }
        $conn = null;
    }

    /**
     * TABLE placeOfWork
     * - placeOfWorkID  => PK
     * - placeOfWorkName
     */
    private function create_placeOfWork_table() {
        try {
            $conn = $this->create_connection();
            if(!$this->check_if_table_exist($conn, "placeOfWork")) {
                $sql = "CREATE TABLE placeOfWork (
                        placeOfWorkID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        placeOfWorkName VARCHAR(40)
                    )";

                $conn ->exec($sql);
                
                echo "placeofWork table was created successfully";
            }
        }
        catch (PDOException $e){
            echo $e->getMessage();
            echo "\r\n";
            echo "Hello World";
        }
        $conn = null;
    }

    public function prepare_database() {
        $this->create_placeOfWork_table();
        $this->create_preoccupation_table();
        $this->create_participantFunction_table();
        $this->create_status_table();
        $this->create_person_table();
    }

    public function prepare_registration() {
        $this->prepare_database();
        return true;
    }

    public function register_participant($username, $password, $email=null) {
        // here: insert a new user into the database.
        // @todo: check if username is free.
        try {
            $conn = $this->create_connection();

            $sql = 'INSERT INTO user(username, password, email, register_date)
            VALUES(?, ?, ?, NOW())';
            $statement = $conn->prepare($sql);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement->execute([$username, $password_hash, $email]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return false;
    }
}