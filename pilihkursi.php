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

    $koneksi->begin_transaction(); // Mulai transaksi
    try {
        foreach ($kursi as $k) {
            $update_kursi->bind_param("i", $k);
            $update_kursi->execute();
        }
        $koneksi->commit(); // Komit transaksi

        // Simpan kursi yang dipilih ke dalam session
        $_SESSION['kursi_terpilih'] = $kursi;

        // Redirect ke halaman tiketmasuk.php
        header("Location: tiketmasuk.php?id_busjadwal=$id_busjadwal");
        exit();
    } catch (Exception $e) {
        $koneksi->rollback(); // Batalkan transaksi jika terjadi kesalahan
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
    <style>
        [tabindex="0"]:focus {
          outline: 2px solid #243642;
          padding: 2px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 20px;
            text-align: center;
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
    </style>
</head>
<body>
    <h2 tabindex="0">Pilih Kursi</h2>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectedSeats = new Set();
            const seatElements = document.querySelectorAll('.seat.available');
            const seatForm = document.getElementById('seatForm');
            const kursiTerpilih = document.getElementById('kursiTerpilih');

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

            seatForm.addEventListener('submit', (e) => {
                kursiTerpilih.value = JSON.stringify([...selectedSeats]);
            });
        });
    </script>
</body>
</html>
