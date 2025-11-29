<?php
abstract class Endpoint {
    protected ?PDO $dbh;

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

    abstract public function handleRequest();
}
