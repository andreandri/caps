<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket Bus</title>
    <link rel="stylesheet" href="pesan.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>[LOGO] [Nama Web]</h1>
    </div>

    <div class="menu">
        <button>Home</button>
        <button>Profile</button>
        <button>History</button>
        <button>About Us</button>
    </div>

    <div class="main">
        <a href="#" class="back-btn">&lt;&lt; Back</a>
        <h2>Pemesanan Tiket Bus</h2>

        <div class="form-container">
            <form action="process_booking.php" method="POST">
                <label for="from">From / Asal :</label>
                <select id="from" name="from">
                    <option value="">Pilih salah satu</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                    <!-- Tambahkan pilihan lain sesuai kebutuhan -->
                </select>

                <label for="to">To / Tujuan :</label>
                <select id="to" name="to">
                    <option value="">Pilih salah satu</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                    <!-- Tambahkan pilihan lain sesuai kebutuhan -->
                </select>

                <label for="departure">Departure / Keberangkatan :</label>
                <!-- Mengubah input text menjadi input date -->
                <input type="date" id="departure" name="departure">

                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
