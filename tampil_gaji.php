<?php 

session_start();

if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>;";
}
require 'functions.php';

//filter
$filter_tgl_awal   = strip_tags($_POST['tgl_awalf']);
$filter_tgl_akhir  = strip_tags($_POST['tgl_akhirf']);


$resultID = mysqli_query($conn, "SELECT id_pegawai FROM peg WHERE id_pegawai = '$_POST[fPilihPeg]'");
$rowID = mysqli_fetch_assoc($resultID);
$id_pegawai = $rowID['id_pegawai'];


$result = mysqli_query($conn, "SELECT * FROM pemasukan WHERE id_pegawai = '$id_pegawai'  
                       AND tgl_input BETWEEN '$filter_tgl_awal' AND 
                       DATE_ADD('$filter_tgl_akhir', INTERVAL 1 DAY) ORDER BY tgl_input DESC");

$total = mysqli_num_rows($result);

$resultNama = mysqli_query($conn, "SELECT nama FROM peg WHERE id_pegawai = '$_POST[fPilihPeg]' ");
$rowNama = mysqli_fetch_assoc($resultNama);
$ambilNama = $rowNama['nama'];

$queryTotal = mysqli_query($conn, "SELECT SUM(CAST(harga_input AS DECIMAL)) AS total FROM pemasukan
WHERE nama_input = '$ambilNama' AND tgl_input BETWEEN ' $filter_tgl_awal' AND DATE_ADD('$filter_tgl_akhir', INTERVAL 1 DAY)");

$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalPem = $rowTotal['total'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootsrap Template</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style/style.css" />

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
      crossorigin="anonymous"
    ></script>


    <!-- Box icons -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <style>
      .scrollable{
        height: 350px;
        overflow-x: hidden;
        overflow-y: scroll;
      }
    </style>
  </head>
  <body>
    <div class="d-flex" id="wrapper">
      <!-- Sidebar Starts -->
      <div
        class="d-flex flex-column flex-shrink-0 p-3 bg-dark" id="sidebar-wrapper"
        style="width: 15rem; height: 100vh"
      >
      <div class="sidebar-content">
        <a
          href="dashboard.php"
          class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
        >
          <span class="title fs-6 text-center fw-bold"
            ><strong> SI2P<br />PENCUCIAN SEPEDA MOTOR SALSA</strong></span
          >
        </a>
        <hr />
        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <a href="dashboard.php" class="nav-link text-white">
              <i class="bx bxs-dashboard"></i>
              Dashboard
            </a>
          </li>

          <?php if($_SESSION["status"] == "Owner") : ?>
          <li>
            <a href="datapeg.php" class="nav-link text-white">
              <i class="bx bx-user"></i>
              Data Pegawai
            </a>
          </li>
          <?php endif; ?>

          <li>
            <a href="pemasukan.php" class="nav-link text-white">
              <i class="bx bx-money"></i>
              Pemasukan
            </a>
          </li>
          <li>
            <a href="gajipeg.php" class="nav-link text-white">
              <i class="bx bxs-wallet"></i>
              Gaji Pegawai
            </a>
          </li>
          <li>
            <a href="edit.php" class="nav-link text-white">
              <i class="bx bx-edit-alt"></i>
              Daftar Harga
            </a>
          </li>
        </ul>
        <hr />
        <div class="logout text-center">
          <a href="login/login.php" class="text-decoration-none text-white">
            <strong>Logout</strong>
          </a>
        </div>
        </div>
      </div>
      <!-- sidebar end -->

      <!-- navigation bar starts-->
      <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light py-4 px-4">
          <div class="d-flex align-items-center">
            <i class="bx bx-menu" id="menu-toggle"></i>
            <h2 class="fs-3 m-0">Menu Filter</h2>
          </div>
        </nav>
        <!-- navigation bar ends -->
        <!-- Dashboard Content Starts -->
 <!-- row starts -->
 <div class="row">
            <!-- column starts -->
            <div class="col-md-11 mx-auto mt-3">
                <!-- card starts -->
            <div class="card">
        <div class="card-header bg-success text-light">
            Filter
        </div>
        <div class="card-body">
          <h5>Total Kendaraan: <?= $total;?></h5>
          <h5>Total Pemasukan: Rp. <?= number_format($totalPem,0,',','.'); ?></h5>

            <!-- table starts -->
            <div class="scrollable">
            <table class="table table-striped table-bordered">
            
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Kendaraan</th>
                <th>Harga</th>
                <th>Nama</th>
            </tr>
            
            <?php $i = 1; ?>
            <?php if (mysqli_num_rows($result) > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            
            <tr>
                <td> <?= $i; ?> </td>
                <td> <?= $row['tgl_input']; ?> </td>
                <td> <?= $row['jenis_input']; ?> </td>
                <td> Rp. <?= number_format($row['harga_input'],0,',','.'); ?> </td>
                <td> <?= $row['nama_input']; ?> </td>
                
            </tr>
            <?php $i++; ?>

            <?php endwhile; ?>
            <?php else :
              echo "Tidak ada data yang ditemukan!"; 
              ?>
            <?php endif; ?>

            </table>
            </div>
            <!-- table ends -->
        </div>
        <div class="card-footer bg-success">
        </div>
        </div>
        <!-- card ends -->
        <a href="gajipeg.php" class="btn btn-info mx-auto" >Kembali</a>
            </div>
            <!-- columns ends -->
            
        </div>
        <!-- row ends -->

 
   
    <!-- Dashboard Content Ends -->
      </div>
    </div>

    
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
      var el =document.getElementById("wrapper")
      var toggleButton = document.getElementById("menu-toggle")
      toggleButton.onclick =function (){
        el.classList.toggle("toggled")
      }


    </script>
  </body>
</html>
