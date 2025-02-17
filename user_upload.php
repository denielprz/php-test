#!/usr/bin/env php

<?php

class UserUpload{
    private $options = [];
    // Set dry run option to false by default
    private $dryRun = false;

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

    public function run(){
        try{
            // Placeholder for the main logic of the script
            echo "Running the script...\n";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$userUpload = new UserUpload();
$userUpload->run();