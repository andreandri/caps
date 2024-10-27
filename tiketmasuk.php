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
    <form action="">
      <h1>Tiket 1</h1>
      <div>
        <label for="gender" tabindex="0"></label>
          <select id="gender" aria-label="Pilih Salah Satu" tabindex="0">
            <option value="">Pilih Gender</option>
            <option value="Tuan">Tuan</option>
            <option value="Per">Per</option>
            <option value="Anak">Anak</option>
          </select>
      </div>

      <div>
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Masukkan Nama Anda">
      </div>

      <div class="search">
        <button type="submit">lanjut</button>
      </div>
    </form>
  </main>
</body>
</html>
