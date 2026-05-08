<?php
include 'koneksi.php';

// Inisialisasi variabel kosong untuk mode 'Tambah'
$id = "";
$judul_buku = "";
$nama_pengarang = "";
$tahun_terbit = "";
$foto_lama = "";
$mode = "Tambah";

// Cek jika ini mode 'Edit'
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM buku WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $judul_buku = $data['judul_buku'];
        $nama_pengarang = $data['nama_pengarang'];
        $tahun_terbit = $data['tahun_terbit'];
        $foto_lama = $data['foto'];
        $mode = "Edit";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $mode; ?> Data Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?= $mode; ?> Data Buku Perpustakaan</h2>
        <form action="proses.php" method="POST" enctype="multipart/form-data" onsubmit="return validasiForm()">
            <input type="hidden" name="aksi" value="<?= strtolower($mode); ?>">
            <input type="hidden" name="id" id="id_buku" value="<?= $id; ?>">
            <input type="hidden" name="foto_lama" value="<?= $foto_lama; ?>">

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" id="judul_buku" value="<?= $judul_buku; ?>">
            </div>

            <div class="form-group">
                <label>Nama Pengarang</label>
                <input type="text" name="nama_pengarang" id="nama_pengarang" value="<?= $nama_pengarang; ?>">
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" value="<?= $tahun_terbit; ?>">
            </div>

            <div class="form-group">
                <label>Foto Sampul (Maks. 2MB, JPG/PNG)</label>
                <?php if($foto_lama != "") { ?>
                    <br><img src="uploads/<?= $foto_lama; ?>" width="100" style="margin-bottom: 10px;">
                <?php } ?>
                <input type="file" name="foto" id="foto" accept="image/jpeg, image/jpg, image/png">
                <small>*Biarkan kosong jika tidak ingin mengubah foto (saat edit).</small>
            </div>

            <button type="submit" class="btn btn-simpan">Simpan Data</button>
            <a href="index.php" class="btn btn-hapus">Batal</a>
        </form>
    </div>

    <!-- Validasi JavaScript -->
    <script>
        function validasiForm() {
            const judul = document.getElementById('judul_buku').value;
            const pengarang = document.getElementById('nama_pengarang').value;
            const tahun = document.getElementById('tahun_terbit').value;
            const foto = document.getElementById('foto');
            const id_buku = document.getElementById('id_buku').value;

            // Cek field kosong
            if (judul.trim() === "" || pengarang.trim() === "" || tahun.trim() === "") {
                alert("Semua inputan teks wajib diisi!");
                return false;
            }

            // Validasi File Foto
            if (foto.files.length > 0) {
                const file = foto.files[0];
                const validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                
                // Cek ekstensi
                if (!validExtensions.includes(file.type)) {
                    alert("Format file tidak valid! Harap unggah JPG, JPEG, atau PNG.");
                    return false;
                }
                // Cek ukuran (Maks 2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert("Ukuran file terlalu besar! Maksimal 2 MB.");
                    return false;
                }
            } else {
                // Jika mode tambah dan foto kosong
                if (id_buku === "") {
                    alert("Foto sampul wajib diunggah untuk data baru!");
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>