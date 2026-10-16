<?php

/**
 * Open a database connection using environment variables.
 *
 * Railway's MYSQL* names work out of the box. The DB_* aliases make the app
 * portable to other hosts and local development environments.
 */
function db_connect(): mysqli
{
    $host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1';
    $port = (int) (getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306);
    $user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: '';
    $password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
    $database = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: '';

    if ($user === '' || $database === '') {
        db_fail('Database configuration is missing. Set DB_USER and DB_NAME (or the Railway MYSQL variables).');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $user, $password, $database, $port);
        $connection->set_charset('utf8mb4');
        db_initialize_if_needed($connection, $database);

        return $connection;
    } catch (Throwable $error) {
        error_log('Database connection/setup failed: ' . $error->getMessage());
        db_fail('The database is temporarily unavailable. Check the server configuration and try again.');
    }
}

/** Seed a blank database from the course project's original schema and demo data. */
function db_initialize_if_needed(mysqli $connection, string $database): void
{
    $autoInitialize = strtolower(getenv('DB_AUTO_INIT') ?: 'true');
    if (in_array($autoInitialize, ['0', 'false', 'no', 'off'], true)) {
        return;
    }

    // The advisory lock prevents two first requests from initializing at once.
    $connection->query("SELECT GET_LOCK('student_tutoring_schema_init', 30)");

    try {
        $statement = $connection->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?'
        );
        $tableName = 'student';
        $statement->bind_param('ss', $database, $tableName);
        $statement->execute();
        $statement->bind_result($tableCount);
        $statement->fetch();
        $statement->close();

        if ((int) $tableCount > 0) {
            return;
        }

        $schema = file_get_contents(__DIR__ . '/project8.sql');
        if ($schema === false) {
            throw new RuntimeException('Could not read project8.sql');
        }

        $connection->multi_query($schema);
        do {
            if ($result = $connection->store_result()) {
                $result->free();
            }
        } while ($connection->more_results() && $connection->next_result());
    } finally {
        $connection->query("SELECT RELEASE_LOCK('student_tutoring_schema_init')");
    }
}

function db_fail(string $message): void
{
    http_response_code(503);
    exit('<!doctype html><html lang="en"><meta charset="utf-8"><title>Configuration error</title>'
        . '<body style="font:16px system-ui;max-width:700px;margin:4rem auto;padding:0 1rem">'
        . '<h1>Student Tutoring Help</h1><p>' . htmlspecialchars($message) . '</p></body></html>');
}
