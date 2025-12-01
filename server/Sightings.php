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
            http_response_code(204);
            return;
        }

        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function handlePost(): void
    {
        function deathTypeFilter(string $value): string|false {
            $validDeathTypes = ['fence', 'fenceElectrocuted', 'road', 'other'];
            if (in_array($value, $validDeathTypes, true)) {
                return $value;
            }

            return false;
        };
        $deathType = filter_input(INPUT_POST, 'deathType', FILTER_CALLBACK, ['options' => deathTypeFilter(...)]);

        // These will be integers/floats if their POST parameters exists and can be parsed.
        $time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
        $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
        $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
        $accuracy = filter_input(INPUT_POST, 'accuracy', FILTER_VALIDATE_FLOAT);

        if (
            $deathType === false
            || !is_int($time)
            || !is_float($latitude)
            || !is_float($longitude)
            || !is_float($accuracy)
        ) {
            http_response_code(400);
            return;
        }

        http_response_code(201);
        header('Content-Type: application/json');

        echo json_encode([$time], JSON_PRETTY_PRINT);
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