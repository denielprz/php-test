#!/usr/bin/env php
<?php

// Autoload the required classes
// require_once 'vendor/autoload.php';

class UserUpload{
    private $options = [];
    // Set dry run option to false by default
    private $dryRun = false;
    // Set the db connection
    private $dbConnection = null;

    public function __construct() {
        $this->parseCommandLineOptions();
    }

    private function parseCommandLineOptions() {
        // Parse command line options for user entry into the table
        $options = getopt('u:p:h:', ['file:', 'create_table', 'dry_run', 'help']);
        $this->options = $options;
        // Set dry run option if it is set by the user
        $this->dryRun = isset($options['dry_run']);

        // Show help message if the user requests it
        if (isset($options['help'])) {
            $this->showHelp();
            exit(0);
        }
    }

    // Show help message, including directives for how to run the script
    private function showHelp() {
        echo "Usage: user_upload.php [options]\n";
        echo "  -u <username> PostgreSQL username\n";
        echo "  -p <password> PostgreSQL password\n";
        echo "  -h <hostname> PostgreSQL hostname\n";
        echo "  --file <filename> CSV file to be processed that includes user data\n";
        echo "  --create_table Create the empty PostgreSQL table\n";
        echo "  --dry_run Parse the CSV file but do not insert into the database\n";
        echo "  --help Show this help message\n";
    }

    // Method to connect to the database
    private function connectToDatabase() {
        // Escape if the connection is already established
        if ($this->dbConnection) {
            return;
        }

        // Connect to the database
        $host = $this->options['h'] ?? 'localhost';
        $user = $this->options['u'] ?? null;
        $password = $this->options['p'] ?? null;

        // Ensure credentials are provided
        if (!$user || !$password) {
            throw new Exception("Please provide the username and password for the database connection.");
        }

        // Connect to the database
        try {
            $dsn = "pgsql:host=$host;dbname=postgres";
            $this->dbConnection = new PDO($dsn, $user, $password);
            // Set the error mode to exceptions
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error connecting to the database: " . $e->getMessage());
        }
    }

    // Method to create the table in the database
    private function createTable() {
        // Connect to the database
        $this->connectToDatabase();

        // Create the table if it does not exist
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            surname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
        )";
        
        try{
            $this->dbConnection->exec($sql);
            echo "Table created successfully\n";
        } catch (PDOException $e) {
            throw new Exception("Error creating table: " . $e->getMessage());
        }
    }

    // Method to validate the email address
    private function validateEmail($email) {
        // Check if the email contains only allowed characters and one @ symbol
        // Regex can be modified to allow more characters if needed
        // For example, some email providers allow the + character and can be added to the regex
        // Regex was included for more strict validation
        if (!preg_match('/^(?!.*\.\.)[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            return false;
        }
        
        // Validate the email address using PHP's built-in filter
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    // Run function of the script
    public function run() {
        try{
            if (isset($this->options['create_table'])) {
                $this->createTable();
                return;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$userUpload = new UserUpload();
$userUpload->run();