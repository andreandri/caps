<?php
include("koneksi.php");
session_start();

$username = $_SESSION['username'];

$id_busjadwal = isset($_GET['id_busjadwal']) ? intval($_GET['id_busjadwal']) : 0;

$sql = "SELECT id_kursi, nomor_kursi, status 
        FROM tb_kursi 
        WHERE id_bus = (SELECT id_bus FROM tb_busjadwal WHERE id_busjadwal = ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_busjadwal);
$stmt->execute();
$result = $stmt->get_result();

$kursi_posisi = [
    ["A1", "B1", "", "C1", "D1"], 
    ["A2", "B2", "", "C2", "D2"], 
    ["A3", "B3", "", "C3", "D3"], 
    ["A4", "B4", "", "C4", "D4"], 
    ["A5", "B5", "", "C5", "D5"],
    ["", "", "", "C6", "D6"]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kursi_terpilih'])) {
    $kursi = json_decode($_POST['kursi_terpilih'], true);
    $update_kursi = $koneksi->prepare("UPDATE tb_kursi SET status = 'selected' WHERE id_kursi = ?");

    $koneksi->begin_transaction();
    try {
        foreach ($kursi as $k) {
            $update_kursi->bind_param("i", $k);
            $update_kursi->execute();
        }
        $koneksi->commit();

        $_SESSION['kursi_terpilih'] = $kursi;

        header("Location: tiketmasuk.php?id_busjadwal=$id_busjadwal");
        exit();
    } catch (Exception $e) {
        $koneksi->rollback(); 
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi</title>
    <link rel="icon" href="favicon.png" type="image/png">
    <script type="module" src="scripts/index.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }


        [tabindex="0"]:focus {
            outline: 2px solid #243642;
            border-radius: 0.4rem;

        }
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }

        main {
            padding-top: 1rem;
        }

        .seat-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin: 20px auto;
            max-width: 500px;
        }

        .seat {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .seat.available {
            background-color: #bbb;
        }

        .seat.booked {
            background-color: #ff4d4d;
            cursor: not-allowed;
        }

        .seat.selected {
            background-color: #4CAF50;
            transform: scale(1.1);
        }

        .seat:hover {
            background-color: #ffcc00;
            transform: scale(1.05);
        }

        .seat.sopir {
            background-color: #333;
            color: #fff;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #004dff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0033cc;
        }
        .legend {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
            border: 1px solid #000;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            width: 150px;
            place-self: center;
        }

        .legend div {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend .color-box {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
        }

        .color-available {
            background-color: gray;
        }

        .color-booked {
            background-color: red;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup.show {
            display: flex;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .popup-content h2 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .popup-content button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }

        .popup-content button:hover {
            background-color: #45a049;
        }

        .popup-content .cancel-btn {
            background-color: #ff4d4d;
        }

        .popup-content .cancel-btn:hover {
            background-color: #d11a2a;
        }

    </style>
</head>
<body>
    <header>
        <bar-pesan-app></bar-pesan-app>
    </header>
    <main>
        <ind-loading-main></ind-loading-main>
        <h2 tabindex="0">Pilih Kursi</h2>
        <div class="legend">
            <div><div class="color-box color-available"></div>Belum Terisi</div>
            <div><div class="color-box color-booked"></div>Sudah Terisi</div>
        </div>
        <form action="" method="POST" id="seatForm">
            <input type="hidden" name="kursi_terpilih" id="kursiTerpilih">
            <div class="seat-container">
                <?php
                foreach ($kursi_posisi as $baris) {
                    foreach ($baris as $posisi) {
                        $status_class = '';
                        $kursi_id = null;
                        $kursi_nomor = $posisi;

                        if ($posisi != "" && $posisi != "SOPIR") {
                            $sql_kursi = "SELECT id_kursi, status FROM tb_kursi WHERE nomor_kursi = ? AND id_bus = (SELECT id_bus FROM tb_busjadwal WHERE id_busjadwal = ?)";
                            $stmt_kursi = $koneksi->prepare($sql_kursi);
                            $stmt_kursi->bind_param("si", $posisi, $id_busjadwal);
                            $stmt_kursi->execute();
                            $result_kursi = $stmt_kursi->get_result();
                            $row = $result_kursi->fetch_assoc();

                            if ($row) {
                                $kursi_id = $row['id_kursi'];
                                $status_class = $row['status'] === 'booked' || $row['status'] === 'selected' ? 'booked' : 'available';
                            }
                        }

                        if ($posisi != "SOPIR") {
                            echo "<div class='seat $status_class' data-id='$kursi_id' data-nomor='$kursi_nomor'>$kursi_nomor</div>";
                        } else {
                            echo "<div class='seat sopir'>$posisi</div>";
                        }
                    }
                }
                ?>
            </div>
            <button tabindex="0" type="submit">Konfirmasi</button>
        </form>
        <div class="popup" id="confirmationPopup">
                    <div class="popup-content">
                        <h2>Apakah Anda yakin dengan pilihan Anda?</h2>
                        <button id="confirmSubmit">Ya</button>
                        <button class="cancel-btn" id="cancelPopup">Tidak</button>
                    </div>
                </div>
        <script>
                document.addEventListener('DOMContentLoaded', () => {
                const selectedSeats = new Set();
                const seatElements = document.querySelectorAll('.seat.available');
                const seatForm = document.getElementById('seatForm');
                const kursiTerpilih = document.getElementById('kursiTerpilih');
                const confirmationPopup = document.getElementById('confirmationPopup');
                const confirmSubmit = document.getElementById('confirmSubmit');
                const cancelPopup = document.getElementById('cancelPopup');
                const confirmButton = document.querySelector('button[type="submit"]');

                seatElements.forEach(seat => {
                    seat.addEventListener('click', () => {
                        const seatId = seat.dataset.id;

                        if (selectedSeats.has(seatId)) {
                            selectedSeats.delete(seatId);
                            seat.classList.remove('selected');
                            seat.classList.add('available');
                        } else {
                            selectedSeats.add(seatId);
                            seat.classList.remove('available');
                            seat.classList.add('selected');
                        }
                    });
                });

                confirmButton.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    confirmationPopup.style.display = 'flex';
                });

                cancelPopup.addEventListener('click', () => {
                    confirmationPopup.style.display = 'none'; 
                });

                confirmSubmit.addEventListener('click', () => {
                    confirmationPopup.style.display = 'none';
                    kursiTerpilih.value = JSON.stringify([...selectedSeats]);
                    seatForm.submit();
                });
            });
        </script>
    </main>
</body>
</html>