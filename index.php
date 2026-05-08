<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Data Buku Perpustakaan</h2>
        <a href="form.php" class="btn btn-tambah">Tambah Data Buku
        
        </a>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto Sampul</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM buku ORDER BY id DESC";
                $result = mysqli_query($conn, $query);
                $no = 1;

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="uploads/<?= $row['foto']; ?>" alt="Sampul" width="80" class="thumbnail"></td>
                    <td><?= htmlspecialchars($row['judul_buku']); ?></td>
                    <td><?= htmlspecialchars($row['nama_pengarang']); ?></td>
                    <td><?= htmlspecialchars($row['tahun_terbit']); ?></td>
                    <td>
                        <a href="form.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="proses.php?aksi=hapus&id=<?= $row['id']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Hapus</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data buku.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>