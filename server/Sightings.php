<?php
// Set up autoloader as described by: https://www.php.net/manual/en/language.oop5.autoload.php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

class Sightings extends Endpoint {
    private function handleGet(): void
    {
        $sth = $this->dbh->prepare(<<<'SQL'
        SELECT id, image, deathType, time
        FROM sighting
        SQL);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = null;

        if ($result === []) {
            http_response_code(404);
            return;
        }

        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function handlePost(): void
    {
        http_response_code(201);
        header('Content-Type: application/json');

        echo json_encode([], JSON_PRETTY_PRINT);
    }

    public function handleRequest(): void
    {
        match ($_SERVER['REQUEST_METHOD']) {
            'GET' => $this->handleGet(),
            'POST' => $this->handlePost(),
            default => http_response_code(405),
        };
    }
}

try {
    $api = new Sightings();
    $api->handleRequest();
} catch (PDOException) {
    http_response_code(500);
}