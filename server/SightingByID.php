<?php
// Set up autoloader as described by: https://www.php.net/manual/en/language.oop5.autoload.php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

class SightingByID extends Endpoint {
    private function handleGet(): void
    {
        // This will be an integer if the id GET parameter exists and is an integer.
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!is_int($id)) {
            http_response_code(400);
            return;
        }

        $sth = $this->dbh->prepare(<<<'SQL'
        SELECT id, image, deathType, time, ST_Latitude(location) as latitude, ST_Longitude(location) as longitude, accuracy, notes
        FROM sighting
        WHERE id = ?
        SQL);
        $sth->execute([$id]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $sth = null;

        if ($result === false) {
            http_response_code(404);
            return;
        }

        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function handleRequest(): void
    {
        match ($_SERVER['REQUEST_METHOD']) {
            'GET' => $this->handleGet(),
            default => http_response_code(405),
        };
    }
}

try {
    $api = new SightingByID();
    $api->handleRequest();
} catch (PDOException) {
    http_response_code(500);
}