<?php
class RestAPI {
    private ?PDO $dbh;

    function __construct()
    {
        // Establish connection to the database.
        $this->dbh = new PDO(
            'mysql:host=localhost;dbname=bsh23_ci609',
            'bsh23_zap_app',
            'qukdoq-dyngis-4fyhVa'
        );
    }

    function __destruct()
    {
        // Close connection to the database.
        $this->dbh = null;
    }

    public function handleRequest(): void
    {
        match ($_SERVER['REQUEST_METHOD']) {
            default => http_response_code(405),
        };
    }
}

try {
    $api = new RestAPI();
    $api->handleRequest();
} catch (PDOException) {
    http_response_code(500);
}