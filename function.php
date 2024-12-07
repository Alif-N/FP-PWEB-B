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
            window.location.href=login.php
        </script>
        ';
    }
}


?>