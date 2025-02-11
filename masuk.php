<?php
require 'ceklogin.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Stock Barang</title>
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
                        <h1 class="mt-4">Data Barang Masuk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                            <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                                Tambah Barang Masuk
                            </button>                                      

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Barang Masuk
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        $get = mysqli_query($conn, "select * from masuk m, produk p where m.idProduk=p.idProduk");
                                        $i = 1;

                                        while($p = mysqli_fetch_array($get))
                                        {
                                            $namaProduk = $p['namaProduk'];
                                            $deskripsi = $p['deskripsi'];
                                            $qty = $p['qty'];
                                            $tanggal = $p['tanggalMasuk'];
                                            $idMasuk = $p['idMasuk'];
                                            $idProduk = $p['idProduk'];
                                    ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$namaProduk?> <?=$deskripsi?></td>
                                            <td><?=$qty?></td>
                                            <td><?=$tanggal?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idMasuk;?>">
                                                    Edit
                                                </button>  
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idMasuk;?>">
                                                    Delete
                                                </button>                                                   
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?=$idMasuk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                            
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Ubah Data Barang Masuk</h4>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                
                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <input type="text" name="namaProduk" class="form-control" placeholder="Nama Produk" value="<?=$namaProduk?> - <?=$deskripsi?>" disabled>
                                                        <input type="number" name="qty" class="form-control mt-2" placeholder="Jumlah" value="<?=$qty?>"> 
                                                        <input type="hidden" name="idMasuk" value="<?=$idMasuk?>"> 
                                                        <input type="hidden" name="idProduk" value="<?=$idProduk?>"> 
                                                    </div>
                                                    
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="editBarangMasuk">Submit</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                                
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?=$idMasuk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                            
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Data Barang Masuk</h4>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                
                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        Apakah anda yakin untuk menghapus data ini?
                                                        <input type="hidden" name="idProduk" value="<?=$idProduk?>"> 
                                                        <input type="hidden" name="idMasuk" value="<?=$idMasuk?>"> 
                                                    </div>
                                                    
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="hapusBarangMasuk">Delete</button>
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
                        <div class="text-muted">Copyright &copy; Kuliah Pemrograman Web Jurusan Teknik Informatika ITS (2024). Dosen: Imam Kuswardayan, S.Kom, M.T.</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
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
                        $getProduk = mysqli_query($conn, "select * from produk");

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
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="barangMasuk">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
            
            
            </div>
        </div>
    </div> 
</html>
