<?php
include("koneksi.php");
session_start();

$username = $_SESSION['username'];

// Validasi parameter id_jadwal
$id_busjadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : 0;
if ($id_busjadwal <= 0) {
    echo "Jadwal tidak valid!";
    exit;
}

// Ambil data kursi berdasarkan jadwal
$query = "SELECT * FROM tb_kursi WHERE id_busjadwal = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$result = $stmt->get_result();

$kursi = [];
while ($row = $result->fetch_assoc()) {
    $kursi[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            width: 400px;
            margin: 20px auto;
            gap: 10px;
            justify-content: center;
        }
        .kursi {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }
        .available { background-color: #ccc; }
        .selected { background-color: blue; color: white; }
        .booked { background-color: red; color: white; cursor: not-allowed; }
        .container-actions {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:disabled {
            background-color: #999;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Pilih Kursi</h1>
    <div class="container">
        <?php foreach ($kursi as $k): ?>
            <div 
                class="kursi <?= $k['status'] ?>" 
                data-id="<?= $k['id_kursi'] ?>" 
                onclick="selectKursi(this)" 
                <?= $k['status'] === 'booked' ? 'style="pointer-events: none;"' : '' ?>>
                <?= htmlspecialchars($k['nomor_kursi']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="container-actions">
        <button id="confirmButton" disabled onclick="confirmKursi()">Konfirmasi Pilihan</button>
    </div>

    <script>
        let selectedSeats = [];

        // Pilih atau batalkan pilihan kursi
        function selectKursi(element) {
            const id = element.getAttribute('data-id');
            if (element.classList.contains('available')) {
                element.classList.remove('available');
                element.classList.add('selected');
                selectedSeats.push(id);
            } else if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                element.classList.add('available');
                selectedSeats = selectedSeats.filter(seat => seat !== id);
            }
            document.getElementById('confirmButton').disabled = selectedSeats.length === 0;
        }

        // Konfirmasi pilihan kursi dan lanjutkan ke tiketmasuk.php
        function confirmKursi() {
            const url = `tiketmasuk.php?id_jadwal=<?= $id_jadwal ?>&selectedSeats=${selectedSeats.join(',')}`;
            window.location.href = url;
        }
    </script>
</body>
</html>
