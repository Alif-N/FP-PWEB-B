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

    $cariStock = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
    $cariStock2 = mysqli_fetch_array($cariStock);
    $stocksekarang = $cariStock2['stock'];    

    $stockBaru = $stocksekarang + $qty;

    $insertb = mysqli_query($conn, "insert into masuk (idProduk, qty) values('$idProduk', '$qty')");
    $update = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk'");

    if($insertb && $update){
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

    if($update && $hapus){
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

if (isset($_POST['editBarangMasuk'])){
    $qty = $_POST['qty'];
    $idMasuk = $_POST['idMasuk'];
    $idProduk = $_POST['idProduk'];

    $caritahu = mysqli_query($conn, "select * from masuk where idMasuk='$idMasuk'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    $cariStock = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
    $cariStock2 = mysqli_fetch_array($cariStock);
    $stocksekarang = $cariStock2['stock'];

    if($qty >= $qtysekarang)
    {
        $selisih = $qty - $qtysekarang;
        $stockBaru = $stocksekarang + $selisih;

        $query1 = mysqli_query($conn, "update masuk set qty='$qty' where idMasuk='$idMasuk'");
        $query2 = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk'");
    
        if($query1&&$query2){
            header('location:masuk.php');
        } else {
            echo '
            <script>
                alert("Gagal Edit Barang Masuk");
                window.location.href="masuk.php"
            </script>
            ';
        }
    } else
    {
        $selisih = $qtysekarang - $qty;
        $stockBaru = $stocksekarang - $selisih;

        $query1 = mysqli_query($conn, "update masuk set qty='$qty' where idMasuk='$idMasuk'");
        $query2 = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk' ");
    
        if($query1&&$query2){
            header('location:masuk.php');
        } else {
            echo '
            <script>
                alert("Gagal Edit Barang Masuk");
                window.location.href="masuk.php"
            </script>
            ';
        }        
    }
}

if (isset($_POST['hapusBarangMasuk'])){
    $idProduk = $_POST['idProduk'];
    $idMasuk = $_POST['idMasuk'];

    $caritahu = mysqli_query($conn, "select * from masuk where idMasuk='$idMasuk'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    $cariStock = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
    $cariStock2 = mysqli_fetch_array($cariStock);
    $stocksekarang = $cariStock2['stock'];
    
    $stockBaru = $stocksekarang - $qtysekarang;

    $query1 = mysqli_query($conn, "delete from masuk where idMasuk='$idMasuk'");
    $query2 = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk'");

    if($query1 && $query2){
        header('location:masuk.php');
    } else {
        echo '
        <script>
            alert("Gagal Edit Barang Masuk");
            window.location.href="masuk.php"
        </script>
        ';
    }  
}

if(isset($_POST['hapusPesanan']))
{
    $idPesanan = $_POST['idPesanan'];

    $cekData = mysqli_query($conn, "select * from detailpesanan where idpesanan='$idPesanan'");

    while($ok = mysqli_fetch_array($cekData))
    {
        $qty = $ok['qty'];
        $idProduk = $ok['idproduk'];
        $idDetailPesanan = $ok['idDetailPesanan'];

        $cariStock = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
        $cariStock2 = mysqli_fetch_array($cariStock);
        $stocksekarang = $cariStock2['stock'];

        $stockBaru = $stocksekarang + $qty;

        $queryUpdate = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk'");
        
        $queryDelete = mysqli_query($conn, "delete from detailpesanan where idDetailPesanan='$idDetailPesanan'");
    }

    $query = mysqli_query($conn, "delete from pesanan where idPesanan='$idPesanan'");
    
    if($queryUpdate && $queryDelete && $query){
        header('location:index.php');
    } else {
        echo '
        <script>
            alert("Gagal hapus pesanan");
            window.location.href="index.php"
        </script>
        ';
    }    
}

if (isset($_POST['editDetailPesanan'])){
    $qty = $_POST['qty'];
    $idDetailPesanan = $_POST['idDetailPesanan'];
    $idProduk = $_POST['idProduk'];
    $idp = $_POST['idp'];

    $caritahu = mysqli_query($conn, "select * from detailpesanan where idDetailPesanan='$idDetailPesanan'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    $cariStock = mysqli_query($conn, "select * from produk where idProduk='$idProduk'");
    $cariStock2 = mysqli_fetch_array($cariStock);
    $stocksekarang = $cariStock2['stock'];

    if($qty >= $qtysekarang)
    {
        $selisih = $qty - $qtysekarang;
        $stockBaru = $stocksekarang - $selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where idDetailPesanan='$idDetailPesanan'");
        $query2 = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk'");
    
        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>
                alert("Gagal Edit Detail Pesanan");
                window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    } else
    {
        $selisih = $qtysekarang - $qty;
        $stockBaru = $stocksekarang + $selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where idDetailPesanan='$idDetailPesanan'");
        $query2 = mysqli_query($conn, "update produk set stock='$stockBaru' where idProduk='$idProduk' ");
    
        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>
                alert("Gagal Edit Detail Pesanan");
                window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }      
    }
}

?>


