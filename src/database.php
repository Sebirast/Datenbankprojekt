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
     * Create User Table
     * ---
     * Checks if "user" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE user:
     *  - user_id
     *  - username
     *  - password
     *  - email
     *  - register_date
     */
    private function create_person_table() {
        // here: create table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, 'user')) {
                // sql to create table
                $sql = "CREATE TABLE person (
                        person_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        surname VARCHAR(40) NOT NULL,
                        firstname VARCHAR(40) NOT NULL,
                        nickname VARCHAR(40),
                        password VARCHAR(40) NOT NULL,
                        email VARCHAR(60) NOT NULL,
                        birthdate DATE,
                        register_date TIMESTAMP )";
                // use exec() because no results are returned
                $sql = "ALTER TABLE person
                        ADD FOREIGN KEY (function) REFRENCES function(FunctionID)
                        ";
                
                $conn->exec($sql);
                echo "user table created successfully";
            } else {
                // echo "user table already exist.";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    public function prepare_login() {
        $this->create_person_table();
        return true;
    }

    public function prepare_registration() {
        $thisperson_idcreate_person_table();
        return true;
    }

    public function login_user($username, $password) {
        try {
            $conn = $this->create_connection();
            $query = "SELECT * FROM `user` WHERE username = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$username]);

            $user = $statement->fetchAll(PDO::FETCH_CLASS);

            if (empty($user)) {
                return false;
            }

            // user exist, we only look at the first entry.
            $user = $user[0];

            $password_saved = $user->password;
            if (!password_verify($password, $password_saved)) {
                return false;
            }

            // remove the password, we don't want to transfer this anywhere.
            unset($user->password);

            return $user;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return false;
    }

    public function register_user($username, $password, $email=null) {
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