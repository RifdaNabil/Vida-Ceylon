<?php
// =====================
// DATA KAMAR
// =====================
$kamars = [
    ["Standar", 500000, "standar.jpg"],
    ["Deluxe", 700000, "deluxe.jpg"],
    ["Executive", 900000, "executive.jpg"]
];

// =====================
// AMBIL DATA FORM
// =====================
$nama       = isset($_POST['nama']) ? $_POST['nama'] : '';
$gender     = isset($_POST['gender']) ? $_POST['gender'] : '';
$identitas  = isset($_POST['identitas']) ? $_POST['identitas'] : '';
$tipe       = isset($_POST['tipe']) ? $_POST['tipe'] : $kamars[0][0];
$tanggal    = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$durasi     = isset($_POST['durasi']) ? $_POST['durasi'] : '';
$breakfast  = isset($_POST['breakfast']);

$harga = 0;
$total = 0;

// =====================
// CARI HARGA BERDASARKAN TIPE
// =====================
foreach ($kamars as $k) {
    if ($k[0] == $tipe) {
        $harga = $k[1];
        break; // langsung berhenti setelah ketemu
    }
}

// =====================
// PROSES FORM
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // HITUNG TOTAL
    if (isset($_POST['hitung'])) {
        $total = $harga * $durasi;

        if ($durasi > 3) {
            $total = $total - ($total * 0.1); // diskon 10%
        }

        if ($breakfast) {
            $total = $total + 80000;
        }
    }

    // SIMPAN
    if (isset($_POST['simpan'])) {
        echo "<script>
            alert('Pemesanan berhasil!');
            window.location.href='index.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hotel Vida Ceylon</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Vida Ceylon</a>
    <div class="navbar-nav ms-auto">
      <a class="nav-link" href="#produk">Kamar</a>
      <a class="nav-link" href="#pesan">Pesan</a>
      <a class="nav-link" href="#tentang">Tentang Kami</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero text-center py-5 bg-light">
  <div class="container">
    <h1 class="fw-bold">Selamat Datang di Hotel Vida Ceylon</h1>
    <p class="text-muted">Penginapan Nyaman di Tengah Kota</p>
    <a href="#produk" class="btn btn-primary mt-3">Lihat Kamar</a>
  </div>
</section>  

<!-- PRODUK -->
<section id="produk" class="container my-5">
  <h3 class="text-center mb-4">Jenis Kamar</h3>
  <div class="row g-4">
    <?php foreach ($kamars as $k) { ?>
    <div class="col-md-4">
      <div class="card h-100 text-center shadow-sm">
        <img src="img/<?= $k[2] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
        <div class="card-body">
          <h5><?= $k[0] ?></h5>
          <p>Rp <?= number_format($k[1],0,',','.') ?></p>
          <a href="#pesan" class="btn btn-secondary">Pesan</a>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</section>

<!-- DAFTAR HARGA -->
<section id="harga" class="container my-5">
  <h3 class="text-center mb-4">Daftar Harga Kamar</h3>
  <table class="table table-bordered text-center">
    <thead class="table-primary text-white">
      <tr>
        <th>No</th>
        <th>Jenis Kamar</th>
        <th>Harga / Malam</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach ($kamars as $k) { ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $k[0] ?></td>
        <td>Rp <?= number_format($k[1],0,',','.') ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</section>

<!-- FORM PEMESANAN -->
<section id="pesan" class="container my-5">
<div class="card shadow-sm">
<div class="card-header bg-info text-white text-center">
  <h5>Form Pemesanan</h5>
</div>
<div class="card-body">

<form method="POST">
<label>Nama Pemesan</label>
<input type="text" name="nama" class="form-control mb-2" value="<?= $nama ?>">

<label>Jenis Kelamin</label><br>
<input type="radio" name="gender" value="Laki-laki" <?= $gender=='Laki-laki'?'checked':'' ?>> Laki-laki
<input type="radio" name="gender" value="Perempuan" <?= $gender=='Perempuan'?'checked':'' ?>> Perempuan
<br><br>

<label>Nomor Identitas</label>
<input type="text" name="identitas" class="form-control mb-2" value="<?= $identitas ?>">

<label>Tipe Kamar</label>
<select name="tipe" class="form-select mb-2" onchange="this.form.submit()">
<?php foreach ($kamars as $k) { ?>
<option value="<?= $k[0] ?>" <?= $tipe==$k[0]?'selected':'' ?>>
<?= $k[0] ?>
</option>
<?php } ?>
</select>

<label>Harga</label>
<input type="text" class="form-control mb-2" value="<?= number_format($harga,0,',','.') ?>" readonly>

<label>Tanggal Pesan</label>
<input type="date" name="tanggal" class="form-control mb-2" value="<?= $tanggal ?>">

<label>Durasi Menginap (hari)</label>
<input type="number" name="durasi" class="form-control mb-2" value="<?= $durasi ?>">

<div class="form-check mb-2">
<input class="form-check-input" type="checkbox" name="breakfast" <?= $breakfast?'checked':'' ?>>
Termasuk Breakfast (+80.000)
</div>

<label>Total Bayar</label>
<input type="text" class="form-control mb-3"
value="<?= $total ? number_format($total,0,',','.') : '' ?>" readonly>

<button name="hitung" class="btn btn-info">Hitung Total</button>
<button name="simpan" class="btn btn-primary">Simpan</button>
<a href="index.php" class="btn btn-danger">Cancel</a>
</form>

</div>
</div>
</section>

<!-- TENTANG KAMI -->
<section id="tentang" class="bg-dark text-white py-4">
<div class="container text-center">
<h5>Tentang Kami</h5>
<p>
Hotel Vida Ceylon<br>
Jalan Ceylon 1D<br>
Telp: 08123456789<br>
Email: info@vidaceylon.com
</p>

<video controls width="100%">
  <source src="video/standar.mp4" type="video/mp4">
</video>

</div>
</section>

<footer class="text-center py-2 border-top">
<small>© 2026 Vida Ceylon</small>
</footer>

</body>
</html>
