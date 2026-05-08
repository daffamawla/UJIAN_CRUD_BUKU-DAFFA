<?php
include 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : $_POST['aksi'];

function uploadFoto() {
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    if ($error === 4) { return false; }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensiFile = explode('.', $namaFile);
    $ekstensiFile = strtolower(end($ekstensiFile));

    if (!in_array($ekstensiFile, $ekstensiValid) || $ukuranFile > 2097152) {
        return false;
    }

    $namaBaru = time() . '_' . uniqid() . '.' . $ekstensiFile;
    move_uploaded_file($tmpName, 'uploads/' . $namaBaru);
    return $namaBaru;
}

if ($aksi == 'tambah') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['nama_pengarang']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    
    $foto = uploadFoto();
    if (!$foto) {
        echo "<script>alert('Gagal upload gambar!'); window.history.back();</script>";
        exit;
    }

    $query = "INSERT INTO buku (judul_buku, nama_pengarang, tahun_terbit, foto) VALUES ('$judul', '$pengarang', '$tahun', '$foto')";
    mysqli_query($conn, $query);

    echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='index.php';</script>";
}

elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['nama_pengarang']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $foto_lama = $_POST['foto_lama'];

    if ($_FILES['foto']['error'] === 4) {
        $foto = $foto_lama;
    } else {
        $foto = uploadFoto();
        if ($foto) {
        
            if (file_exists("uploads/" . $foto_lama)) {
                unlink("uploads/" . $foto_lama);
            }
        } else {
            echo "<script>alert('Gagal upload gambar baru!'); window.history.back();</script>";
            exit;
        }
    }

    $query = "UPDATE buku SET judul_buku='$judul', nama_pengarang='$pengarang', tahun_terbit='$tahun', foto='$foto' WHERE id='$id'";
    mysqli_query($conn, $query);

    echo "<script>alert('Data berhasil diperbarui!'); window.location.href='index.php';</script>";
}

elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    
    $q_foto = "SELECT foto FROM buku WHERE id = '$id'";
    $res_foto = mysqli_query($conn, $q_foto);
    $data_foto = mysqli_fetch_assoc($res_foto);
    
    if ($data_foto && file_exists("uploads/" . $data_foto['foto'])) {
        unlink("uploads/" . $data_foto['foto']);
    }

    $query = "DELETE FROM buku WHERE id='$id'";
    mysqli_query($conn, $query);

    echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php';</script>";
}
?>