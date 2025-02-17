# PHP Test Script

This script inserts a CSV file of users into a PostgreSQL database. 
The script can be run via the command line.

## Prerequisites

Ensure you have the following installed and configured:

- PHP
- PostgreSQL
- Required PHP extensions (e.g., pgsql, pdo_pgsql)

## Usage

Run the script with the following command:

```sh
php user_upload.php --file <path_to_csv_file> -u <PostgreSQL username> -p <PostgreSQL password> -h <PostgreSQL hostname>
```

Replace `<path_to_csv_file>` with the path to your CSV file.

The hostname option is optional and defaults to `localhost` if not provided.

### Command Line Options

The following command line options are available for this script:

- `--help`: Shows the usable options in the command line.
- `--create_table`: Creates the users table in your PostgreSQL database.
- `--dry_run`: Executes the script without making any changes to the database.

## Example

To run the script and insert users from `users.csv`:

```sh
php user_upload.php --file users.csv -u myuser -p mypassword -h localhost
```

To create the users table:

```sh
php user_upload.php --create_table -u myuser -p mypassword -h localhost
```

To perform a dry run:

```sh
php user_upload.php --file users.csv --dry_run -u myuser -p mypassword -h localhost
```

