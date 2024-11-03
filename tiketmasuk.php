<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>

  <script type="module" src="scripts/index.js"></script>

  <link rel="stylesheet" href="tiketmasuk.css">
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>
  
  <main>
    <h1>Pesan Kursi</h1>
    <form action="" class="form">
      <h2>Tiket A1, A3</h2>
      <div class="label">
        <label for="gender" tabindex="0">Pilih Gender</label>
          <select id="gender" aria-label="Pilih Salah Satu" tabindex="0">
<<<<<<< Updated upstream
            <option value="">Jenis Kelamin</option>
            <option value="Laki-laki">Laki-laki</option>
=======
            <option value="">Pilih Gender</option>
            <option value="Tuan">Tuan</option>
>>>>>>> Stashed changes
            <option value="Perempuan">Perempuan</option>
          </select>
      </div>

      <div class="label">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Masukkan Nama Anda">
      </div>

      <div class="label">
        <label for="nomor">No HP</label>
        <input type="number" id="nomor" name="nomor" placeholder="Masukkan Nomor Hp Anda">
      </div>

      <div class="search">
        <button type="submit">lanjut</button>
      </div>
    </form>
  </main>
</body>
</html>
