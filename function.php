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

if(isset($_POST['addProduk']))
{
    $idProduk = $_POST['idProduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];

    $insert = mysqli_query($conn, "insert into detailpesanan (idPesanan, idProduk, qty) values ('$idp', '$idProduk', '$qty')");

    if($insert)
    {
        header('location:view.php?idp='.$idp);
    } else
    {
        echo '
        <script>
            alert("Gagal Menambah Pelanggan Baru")
            window.location.href="view.php?idp="'.$idp.'
        </script>
        ';
    }
}
?>