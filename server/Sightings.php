<?php
// Set up autoloader as described by: https://www.php.net/manual/en/language.oop5.autoload.php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

class Sightings extends Endpoint {
    /**
     * @throws NoContent
     */
    private function handleGet(): void
    {
        $sth = $this->dbh->prepare(<<<'SQL'
        SELECT id, image, deathType, UNIX_TIMESTAMP(time) * 1000 as time
        FROM sighting
        SQL);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = null;

        if ($result === []) {
            throw new NoContent('No sightings');
        }

        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * @throws BadRequest
     */
    private function handlePost(): void
    {
        if (!isset($_FILES['image'])) {
            throw new BadRequest('Missing image POST parameter');
        }
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new BadRequest('Image not OK');
        }

        $tmpName = $_FILES['image']['tmp_name'];
        $uploadedMime = mime_content_type($tmpName);
        $validMimes = ['image/jpeg', 'image/png'];
        if (!in_array($uploadedMime, $validMimes, true)) {
            throw new BadRequest('Image has invalid MIME');
        }

        $fileName = uniqid();
        $uploadDestination = "uploads/$fileName.jpg";
        if (!move_uploaded_file($tmpName, $uploadDestination)) {
            throw new BadRequest('Failed to move tmp image to destination');
        }
        $imageUrl = 'https://bsh23.brighton.domains/ci609/api/' . $uploadDestination;

        function deathTypeFilter(string $value): string|null|false {
            if ($value === "") {
                return null;
            }

            $validDeathTypes = ['fence', 'fenceElectrocuted', 'road', 'other'];
            if (in_array($value, $validDeathTypes, true)) {
                return $value;
            }

            return false;
        }
        $deathType = filter_input(INPUT_POST, 'deathType', FILTER_CALLBACK, ['options' => deathTypeFilter(...)]);

        // These will be integers/floats if their POST parameters exists and can be parsed.
        $time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
        $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
        $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
        $accuracy = filter_input(INPUT_POST, 'accuracy', FILTER_VALIDATE_FLOAT);

        function notesFilter(string $value): string|null|false {
            if ($value === "") {
                return null;
            }

            // Our database stores notes in a VARCHAR(10000), which measures length in character units rather than
            // bytes. Source: https://dev.mysql.com/doc/refman/8.0/en/string-type-syntax.html
            // That's why we measure the length with mb_strlen() instead of strlen().
            $length = mb_strlen($value, 'UTF-8');

            if ($length < 10000) {
                return $value;
            }
            return false;
        }
        $notes = filter_input(INPUT_POST, 'notes', FILTER_CALLBACK, ['options' => notesFilter(...)]);

        if (
            $deathType === false
            || !is_int($time)
            || !is_float($latitude)
            || !is_float($longitude)
            || !is_float($accuracy)
            || $notes === false
        ) {
            throw new BadRequest('Parameter missing or invalid');
        }

        $sth = $this->dbh->prepare(<<<'SQL'
        INSERT INTO sighting 
            (deathType, time, location, accuracy, image, notes)
        VALUES
            (:deathType, FROM_UNIXTIME(:time / 1000), ST_PointFromText(:location, 4326), :accuracy, :image, :notes);
        SQL);
        $sth->execute([
            ":deathType" => $deathType,
            ":time" => $time,
            ":location" => "POINT($latitude $longitude)",
            ":accuracy" => $accuracy,
            ":image" => $imageUrl,
            ":notes" => $notes,
        ]);
        $sth = null;

        $id = intval($this->dbh->lastInsertId());

        http_response_code(201);
        header('Content-Type: application/json');

        echo json_encode(['id' => $id], JSON_PRETTY_PRINT);
    }

    /**
     * @throws BadRequest
     * @throws NoContent
     */
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
} catch (BadRequest) {
    http_response_code(400);
} catch (NoContent) {
    http_response_code(204);
}