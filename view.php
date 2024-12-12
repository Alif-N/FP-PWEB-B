<?php
require 'ceklogin.php';

if(isset($_GET['idp']))
{
    $idp = $_GET['idp'];
    $ambilNamaPelanggan = mysqli_query($conn, "select * from pesanan p, pelanggan pl where p.idPelanggan=pl.idPelanggan and p.idPesanan='$idp'");
    $np = mysqli_fetch_array($ambilNamaPelanggan);
    $namapel = $np['namaPelanggan'];
} else
{
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Detail Pesanan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">        
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Aplikasi Kasir</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Data Pesanan
                            </a>
                            <a class="nav-link" href="stock.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Kelola Pelanggan
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Pesanan: <?=$idp;?></h1>
                        <h4 class="mt-4">Nama Pelanggan: <?=$namapel;?></h4>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                        <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                                Tambah Barang
                        </button>  

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Pesanan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Sub-total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        $get = mysqli_query($conn, "select * from detailpesanan p, produk pr where p.idProduk=pr.idProduk and idPesanan='$idp'");
                                        $i = 1;
                                        

                                        while($p = mysqli_fetch_array($get))
                                        {
                                            $idProduk = $p['idProduk'];
                                            $idDetailPesanan = $p['idDetailPesanan'];
                                            $qty = $p['qty'];
                                            $harga = $p['harga'];
                                            $namaProduk = $p['namaProduk'];
                                            $deskripsi = $p['deskripsi'];
                                            $subtotal = $qty * $harga;
                                    ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$namaProduk?> (<?=$deskripsi?>)</td>
                                            <td>Rp<?=number_format($harga)?></td>
                                            <td><?=number_format($qty)?></td>
                                            <td>Rp<?=number_format($subtotal)?></td>
                                            <td>Edit 
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idProduk;?>">
                                                    Hapus
                                                </button>           
                                            </td>
                                        </tr>

                                          <!-- The Modal -->
                                        <div class="modal fade" id="delete<?=$idProduk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                            
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Apakah anda ingin menghapus barang ini</h4>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                
                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        Apakah Anda Yakin ingin menghapus barang ini?
                                                        <input type="hidden" name="idp" value="<?=$idDetailPesanan;?>">
                                                        <input type="hidden" name="idProduk" value="<?=$idProduk;?>">
                                                        <input type="hidden" name="idPesanan" value="<?=$idp;?>">
                                                    </div>
                                                    
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="hapusProduk">Ya</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                                
                                                
                                                </div>
                                            </div>
                                        </div>   
                                    <?php
                                        }; // end of while
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>  
    </body>

      <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            
            <form method="post">
                <!-- Modal body -->
                <div class="modal-body">
                    Pilih Barang
                    <select name="idProduk" class="form-control">

                    <?php
                        $getProduk = mysqli_query($conn, "select * from produk where idProduk not in (select idProduk from detailpesanan where idPesanan='$idp')");

                        while($pl = mysqli_fetch_array($getProduk))
                        {
                            $namaProduk = $pl['namaProduk'];
                            $deskripsi = $pl['deskripsi'];
                            $stock = $pl['stock'];
                            $idProduk = $pl['idProduk'];
                    ?>
                    
                    <option value="<?=$idProduk;?>"><?=$namaProduk;?> - <?=$deskripsi;?> (Stock: <?=$stock;?>)</option>

                    <?php
                        }
                    ?>

                    </select>

                    <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah" min='1' required>
                    <input type="hidden" name="idp" value="<?=$idp;?>">
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="addProduk">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
            
            
            </div>
        </div>
    </div>    
</html>
