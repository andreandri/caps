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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #b5d3b5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 90vw;
            height: 90vh;
            background-color: #e0e0e0;
            padding: 20px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .back-button, .confirm-button {
            background-color: #a0a0a0;
            color: #333;
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            cursor: pointer;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .seat-layout {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            width: 100%;
            height: 70%;
            margin-top: 20px;
            justify-items: center;
        }
        .seat {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            cursor: pointer;
        }
        .seat.available {
            background-color: gray;
        }
        .seat.booked {
            background-color: red;
            cursor: not-allowed;
        }
        .seat.selected {
            background-color: blue;
        }
        .seat.driver {
            background-color: #ccff99;
            color: black;
            cursor: default;
        }
        .legend {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 20px;
            border: 1px solid #888;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.2em;
        }
        .legend div {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .legend .color-box {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
        }
        .color-available { background-color: gray; }
        .color-booked { background-color: red; }
        .color-selected { background-color: blue; }
        .color-driver { background-color: #ccff99; }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="window.location.href='pesantiket.php'"><< Back</button>
        <div class="seat-layout">
            <?php
            $seatLayout = [
                ["A1", "A2", "", "B2", "B1"],
                ["A3", "A4", "", "B4", "B3"],
                ["A5", "A6", "", "B6", "B5"],
                ["A7", "A8", "", "B8", "B7"],
                ["B9", "B10", "", "", ""]
            ];

            foreach ($seatLayout as $row) {
                foreach ($row as $seat) {
                    if ($seat === "") {
                        echo "<div style='width: 80px; height: 80px;'></div>";
                    } else {
                        $status = isset($seatsData[$seat]) && $seatsData[$seat]['status'] === 'booked' ? 'booked' : 'available';
                        $driverClass = ($seat === 'S') ? 'driver' : '';
                        echo "<div class='seat $status $driverClass' data-seat='$seat' onclick='selectSeat(\"$seat\")'>$seat</div>";
                    }
                }
            }
            ?>
        </div>
        <div class="legend">
            <div><div class="color-box color-available"></div> Belum Terisi</div>
            <div><div class="color-box color-booked"></div> Terisi</div>
            <div><div class="color-box color-selected"></div> Dipilih</div>
            <div><div class="color-box color-driver"></div> Sopir</div>
        </div>
        <button class="confirm-button" onclick="confirmSeats()">KONFIRMASI KURSI</button>
    </div>

    <script>
        const selectedSeats = [];

        function selectSeat(seat) {
            const seatElement = document.querySelector(`[data-seat='${seat}']`);
            if (seatElement.classList.contains('booked') || seatElement.classList.contains('driver')) {
                alert('Seat is not available for selection!');
                return;
            }

            seatElement.classList.toggle('selected');
            if (selectedSeats.includes(seat)) {
                selectedSeats.splice(selectedSeats.indexOf(seat), 1); // Remove seat from selectedSeats
            } else {
                selectedSeats.push(seat); // Add seat to selectedSeats
            }
        }

        function confirmSeats() {
    if (selectedSeats.length === 0) {
        alert('Please select at least one seat!');
        return;
    }

    fetch('save_seat_selection.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ seats: selectedSeats })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Seats confirmed successfully!');
            // Arahkan pengguna ke halaman konfirmasi setelah sukses
            window.location.href = 'tiketmasuk.php';
        } else {
            alert('Failed to confirm seats.');
        }
    })
    .catch(error => console.error('Error:', error));
}

    </script>
</body>
</html>
