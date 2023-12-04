<?php session_start();

if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>;";
}
require 'functions.php';

// hitung total kendaraan
$queryKend = mysqli_query($conn, "SELECT * FROM pemasukan");
$totalKend = mysqli_num_rows($queryKend);

// hitung total pegawai
$queryPeg = mysqli_query($conn, "SELECT * FROM peg WHERE status = 'Pegawai' ");
$totalPeg = mysqli_num_rows($queryPeg);

// hitung total pemasukan
$queryTotal = mysqli_query($conn, "SELECT SUM(CAST(harga_input AS DECIMAL)) AS total FROM pemasukan");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalPem = $rowTotal['total'] / 2;


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
          <span class="title fs-6 text-center fw-bold">
            <strong> SI2P<br />PENCUCIAN SEPEDA MOTOR SALSA</strong>
          </span>
        </a>
        <hr />

        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <a href="#" class="nav-link text-white">
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
          <a href="logout.php" class="text-decoration-none text-white">
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
            <h2 class="fs-3 m-0 mx-2 mb-1">Menu Dashboard</h2>
            <div class="d-inline p-4  fs-4 position-absolute top-25 end-0 fw-bold">
              Selamat Datang, <?= $_SESSION["nama"]; ?>!</div>
          </div>
        </nav>
       
        <!-- navigation bar ends -->

        <!-- Dashboard Content Starts -->
    <div class="container-fluid px-4">
      <div class="row g-2 my-2">
        <div class="col-md-4">
          <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
              <h2 class="fs-4"><?= $totalKend; ?></h2>
              <p class="fs-6">Total Kendaraan Masuk</p>
            </div>
            <i class="bx bx-cycling fs-3 border rounded full secondary-bg p-3" ></i> 
          </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded">
          <div>
            <h2 class="fs-4">Rp <?= number_format($totalPem,0,',','.'); ?></h2>
            <p class="fs-6">Total Pendapatan</p>
        </div>
          <i class="bx bx-money fs-3 border rounded full secondary-bg p-3" ></i> 
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded">
          <div>
            <h2 class="fs-4"><?= $totalPeg; ?></h2>
            <p class="fs-6">Jumlah Pegawai</p>
          </div>
          <i class="bx bxs-user fs-3 border rounded full secondary-bg p-3" ></i> 
        </div>
      </div>
    </div>
    </div>
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
