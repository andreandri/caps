<?php
include("config.php");
include("firebaseRDB.php");

header('Content-Type: application/json');  // Pastikan respons berformat JSON

// Dekode data JSON dari JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$seat = $data['seat'] ?? null;

if ($seat) {
    $db = new firebaseRDB($databaseURL);
    
    // Coba update status kursi menjadi 'booked' di Firebase
    $response = $db->update("busSeats", $seat, ["status" => "booked"]);
    
    // Periksa apakah respons dari Firebase mengandung error atau tidak
    if (strpos($response, 'error') === false) {
        echo json_encode(["success" => true, "message" => "Seat booked successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to book seat.", "error" => $response]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid seat data received."]);
}
