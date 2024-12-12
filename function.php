<?php

session_start();

//koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'kasir');

//login function
if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' and password='$password'");
    $count = mysqli_num_rows($check);

    if($count > 0)
    {
        $_SESSION['login'] = 'True';
        header('location:index.php');
    } else
    {
        echo '
        <script>
            alert("Username atau Password salah")
            window.location.href="login.php"
        </script>
        ';
    }
}

if(isset($_POST['tambahBarang']))
{
    $namaProduk = $_POST['namaProduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    $insert = mysqli_query($conn, "insert into produk (namaProduk, deskripsi, harga, stock) values ('$namaProduk', '$deskripsi', '$harga', '$stock')");

    if($insert)
    {
        header('location:stock.php');
    } else
    {
        echo '
        <script>
            alert("Gagal Menambah Barag Baru")
            window.location.href="stock.php"
        </script>
        ';
    }
}

if(isset($_POST['tambahPelanggan']))
{
    $namaPelanggan = $_POST['namaPelanggan'];
    $noTelp = $_POST['noTelp'];
    $alamat = $_POST['alamat'];

    $insert = mysqli_query($conn, "insert into pelanggan (namaPelanggan, noTelp, alamat) values ('$namaPelanggan', '$noTelp', '$alamat')");

    if($insert)
    {
        header('location:pelanggan.php');
    } else
    {
        echo '
        <script>
            alert("Gagal Menambah Pelanggan Baru")
            window.location.href="pelanggan.php"
        </script>
        ';
    }
}

if(isset($_POST['tambahPesanan']))
{
    $idPelanggan = $_POST['idPelanggan'];

    $insert = mysqli_query($conn, "insert into pesanan (idPelanggan) values ('$idPelanggan')");

    if($insert)
    {
        header('location:index.php');
    } else
    {
        echo '
        <script>
            alert("Gagal Menambah Pelanggan Baru")
            window.location.href="index.php"
        </script>
        ';
    }
}

// Produk dipilih di pesanan
if(isset($_POST['addProduk']))
{
    $idProduk = $_POST['idProduk'];
    $idp = $_POST['idp']; // idPesanan
    $qty = $_POST['qty']; // jumlah

    // Hitung stock barang
    $hitung1 = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocksekarang = $hitung2['stock']; // stock barang saat ini

    // Cek apakah stock cukup
    if($stocksekarang >= $qty){
        // Kurangi stock yang dikeluarkan
        $selisih = $stocksekarang - $qty;

        // Jika stock cukup, update stock barang
        $insert = mysqli_query($conn, "insert into detailpesanan (idPesanan, idProduk, qty) values ('$idp', '$idProduk', '$qty')");
        $update = mysqli_query($conn, "update produk set stock='$selisih' where idProduk='$idProduk'");

        if($insert&&$update){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>
                alert("Gagal Menambah Pelanggan Baru")
                window.location.href="view.php?idp='.$idp.'"
            </script>';
        }
    } else {
        echo '
        <script>
            alert("Stock barang tidak cukup")
            window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
    }
}

// Menambah barang masuk
if (isset($_POST['barangMasuk'])){
    $idProduk = $_POST['idProduk'];
    $qty = $_POST['qty'];

    $insertb = mysqli_query($conn, "insert into masuk (idProduk, qty) values('$idProduk', '$qty')");

    if($insertb){
        header('location:masuk.php');
    } else {
        echo '
        <script>
            alert("Gagal")
            window.location.href="masuk.php"
        </script>
        ';
    }
}

// Hapus produk pesanan
if (isset($_POST['hapusProduk'])){
    $idp = $_POST['idp'];
    $idProduk = $_POST['idProduk'];
    $idPesanan = $_POST['idPesanan'];

    // cek qty sekarang
    $cek1 = mysqli_query($conn, "select * from detailpesanan where idDetailPesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    // Stock sekarang
    $cek3 = mysqli_query($conn, "select * from produk where idProduk='$idp'");
    $cek4 = mysqli_fetch_array($cek3);  
    $stocksekarang = $cek4['stock'];

    $hitung = $stocksekarang + $qtysekarang;

    $update = mysqli_query($conn, "update produk set stock='$hitung' where idProduk='$idProduk'");
    $hapus = mysqli_query($conn, "delete from detailpesanan where idProduk='$idProduk' and idDetailPesanan='$idp'");

    if($update&&$hapus){
        header('location:view.php?idp='.$idPesanan);
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang");
            window.location.href="view.php?idp='.$idPesanan.'"
        </script>
        ';
    }
}

if (isset($_POST['editBarang'])){
    $namaProduk = $_POST['namaProduk'];
    $idProduk = $_POST['idProduk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    $query = mysqli_query($conn, "update produk set namaProduk='$namaProduk', deskripsi='$deskripsi', harga='$harga' where idProduk='$idProduk'");
    
    if($query){
        header('location:stock.php');
    } else {
        echo '
        <script>
            alert("Gagal Edit barang");
            window.location.href="stock.php"
        </script>
        ';
    }
}

if (isset($_POST['hapusBarang'])){
    $idProduk = $_POST['idProduk'];

    $query = mysqli_query($conn, "delete from produk where idProduk='$idProduk'");
    
    if($query){
        header('location:stock.php');
    } else {
        echo '
        <script>
            alert("Gagal Hapus barang");
            window.location.href="stock.php"
        </script>
        ';
    }
}

if (isset($_POST['editPelanggan'])){
    $namaPelanggan = $_POST['namaPelanggan'];
    $noTelp = $_POST['noTelp'];
    $alamat = $_POST['alamat'];
    $idPelanggan = $_POST['idPelanggan'];

    $query = mysqli_query($conn, "update pelanggan set namaPelanggan='$namaPelanggan', noTelp='$noTelp', alamat='$alamat' where idPelanggan='$idPelanggan'");
    
    if($query){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>
            alert("Gagal Edit pelanggan");
            window.location.href="pelanggan.php"
        </script>
        ';
    }
}

if (isset($_POST['hapusPelanggan'])){
    $idPelanggan = $_POST['idPelanggan'];

    $query = mysqli_query($conn, "delete from pelanggan where idPelanggan='$idPelanggan'");
    
    if($query){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>
            alert("Gagal Edit pelanggan");
            window.location.href="pelanggan.php"
        </script>
        ';
    }
}
?>


