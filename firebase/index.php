<?php
include("config.php");
include("firebaseRDB.php");

$db = new firebaseRDB($databaseURL);
$seatsData = json_decode($db->retrieve("busSeats"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Seat Selection</title>
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            display: inline-block;
            background-color: red;
            color: white;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }
        .booked {
            background-color: gray;
            cursor: not-allowed;
        }
        .driver {
            background-color: gold;
            cursor: default;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #d4d4d4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="driver">Sopir</div>
        <div class="seats">
            <?php
            $seatLayout = [
                ["A1", "A2", "", "B2", "B1"],
                ["A3", "A4", "", "B4", "B3"],
                ["A5", "A6", "", "B6", "B5"],
                ["A7", "A8", "", "B8", "B7"],
                ["A9", "A10", "", "B10", "B9"],
                ["", "", "", "B12", "B11"]
            ];

            foreach ($seatLayout as $row) {
                echo "<div class='row'>";
                foreach ($row as $seat) {
                    if ($seat === "") {
                        echo "<div style='width: 40px; height: 40px;'></div>";
                    } else {
                        $status = isset($seatsData[$seat]) && $seatsData[$seat]['status'] === 'booked' ? 'booked' : '';
                        echo "<div class='seat $status' data-seat='$seat' onclick='selectSeat(\"$seat\")'>$seat</div>";
                    }
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        function selectSeat(seat) {
            const seatElement = document.querySelector(`[data-seat='${seat}']`);
            if (seatElement.classList.contains('booked')) {
                alert('Seat is already booked!');
                return;
            }
            fetch('book_seat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ seat })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    seatElement.classList.add('booked');
                    alert('Seat booked successfully!');
                } else {
                    alert('Failed to book seat.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
