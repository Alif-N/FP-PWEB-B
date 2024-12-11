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

?>