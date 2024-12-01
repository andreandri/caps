<?php
include("config.php");
include("firebaseRDB.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$seats = $data['seats'] ?? [];

if (!empty($seats)) {
    $db = new firebaseRDB($databaseURL);
    $successCount = 0;

    foreach ($seats as $seat) {
        $response = $db->update("busSeats", $seat, ["status" => "booked"]);

        if (strpos($response, 'error') === false) {
            $successCount++;
        }
    }

    if ($successCount === count($seats)) {
        echo json_encode(["success" => true, "message" => "Seats confirmed successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to confirm some seats."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No seat data received."]);
}
?>
