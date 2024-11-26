<?php
include("koneksi.php");
session_start();

$id_busjadwal = isset($_GET['id_busjadwal']) ? intval($_GET['id_busjadwal']) : 0;

// Ambil semua data kursi untuk bus tertentu
$sql = "SELECT id_kursi, nomor_kursi, status 
        FROM tb_kursi 
        WHERE id_bus = (SELECT id_bus FROM tb_busjadwal WHERE id_busjadwal = ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_busjadwal);
$stmt->execute();
$result = $stmt->get_result();

// Array untuk menyimpan posisi kursi
$kursi_posisi = [
    ["A1", "B1", "", "C1", "D1"], 
    ["A2", "B2", "", "C2", "D2"], 
    ["A3", "B3", "", "C3", "D3"], 
    ["A4", "B4", "", "C4", "D4"], 
    ["A5", "B5", "", "C5", "D5"],
    ["", "", "", "C6", "D6"]
];

// Proses ketika kursi dikonfirmasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kursi'])) {
    $kursi = $_POST['kursi'];
    $update_kursi = $koneksi->prepare("UPDATE tb_kursi SET status = 'booked' WHERE id_kursi = ?");

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
        body {
            font-family: Arial, sans-serif;
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

        /* Kursi kosong */
        .seat.available {
            background-color: #bbb;
        }

        /* Kursi sudah dipesan */
        .seat.booked {
            background-color: #ff4d4d;
            cursor: not-allowed;
        }

        /* Kursi yang dipilih */
        .seat.selected {
            background-color: #4CAF50;
            transform: scale(1.1);
        }

        /* Kursi yang baru diklik */
        .seat-clicked {
            background-color: #2196F3;
            transform: scale(1.2);
        }

        /* Hover efek untuk kursi */
        .seat:hover {
            background-color: #ffcc00; /* Ganti dengan warna hover sesuai keinginan */
            transform: scale(1.05); /* Efek sedikit membesar saat hover */
        }

        .seat input {
            display: none;
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
    <h2>Pilih Kursi</h2>
    <form action="" method="POST">
        <input type="hidden" name="id_busjadwal" value="<?php echo $id_busjadwal; ?>">
        <div class="seat-container">
            <?php
            // PHP Loop untuk menampilkan kursi
            foreach ($kursi_posisi as $baris) {
                foreach ($baris as $posisi) {
                    // Mengambil status kursi dari database
                    $status_class = '';
                    $disabled = '';
                    $kursi_id = null;
                    $kursi_nomor = $posisi;

                    if ($posisi != " " && $posisi != "SOPIR") {
                        // Cek jika kursi ada di database
                        $sql_kursi = "SELECT id_kursi, status FROM tb_kursi WHERE nomor_kursi = ? AND id_bus = (SELECT id_bus FROM tb_busjadwal WHERE id_busjadwal = ?)";
                        $stmt_kursi = $koneksi->prepare($sql_kursi);
                        $stmt_kursi->bind_param("si", $posisi, $id_busjadwal);
                        $stmt_kursi->execute();
                        $result_kursi = $stmt_kursi->get_result();
                        $row = $result_kursi->fetch_assoc();

                        if ($row) {
                            $kursi_id = $row['id_kursi'];
                            $status_class = $row['status'] === 'booked' ? 'booked' : 'available';
                            $disabled = $row['status'] === 'booked' ? 'disabled' : '';
                        }
                    }

                    // Tampilkan kursi jika ada
                    if ($posisi != "SOPIR") {
                        echo "<label class='seat $status_class'>
                                <input type='checkbox' name='kursi[]' value='$kursi_id' $disabled>
                                $kursi_nomor
                              </label>";
                    } else {
                        // Tampilkan "SOPIR" jika posisi adalah sopir
                        echo "<div class='seat sopir'>$posisi</div>";
                    }
                }
            }
            ?>
        </div>
        <button type="submit">Konfirmasi</button>
    </form>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    // Seleksi semua kursi yang tersedia dan yang sudah dipilih
    const seats = document.querySelectorAll('.seat.available, .seat.selected');

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            const checkbox = seat.querySelector('input[type="checkbox"]');
            
            if (seat.classList.contains('selected')) {
                // Jika kursi sudah dipilih, batalkan pilihan dan kembalikan warna kursi
                seat.classList.remove('selected');
                seat.classList.add('available'); // Mengubah kelas ke "available"
                checkbox.checked = false; // Reset checkbox (kursi dibatalkan)
            } else if (seat.classList.contains('available')) {
                // Jika kursi belum dipilih, pilih kursi dan beri animasi klik
                seat.classList.add('seat-clicked');
                setTimeout(() => {
                    seat.classList.remove('seat-clicked');
                    seat.classList.add('selected');
                    checkbox.checked = true; // Set checkbox ke true (kursi dipilih)
                }, 300); // Durasi animasi sama dengan CSS transition
            }
        });
    });
});


    </script>
</body>
</html>
