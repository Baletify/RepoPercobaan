<?php 
session_start();

if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>;";
}
require 'functions.php';

//Insert data pegawai
if(isset($_POST['tHar'])){
  //cek data sudah berhasil ditambahkan atau belum
  if(insertHarga($_POST) > 0){

    echo "<script>
    alert('Data berhasil ditambahkan!');
    document.location = 'edit.php';
    </script>";
  }
  else{  
    echo "<script>
    alert('Gagal menambahkan data!');
    document.location = 'edit.php';
    </script>";
  }
}

//Insert data pegawai
if(isset($_POST['eHar'])){
  //cek data sudah berhasil ditambahkan atau belum
  if(editHarga($_POST) > 0){

    echo "<script>
    alert('Data berhasil diubah!');
    document.location = 'edit.php';
    </script>";
  }
  else{  
    echo "<script>
    alert('Gagal mengubah data!');
    document.location = 'edit.php';
    </script>";
  }
}

if (isset($_POST['hHar'])){
  //memanggil kueri untuk hapus data
  $hapus = mysqli_query($conn, "DELETE FROM list_harga WHERE id_harga = '$_POST[id_harga]' ");
                                                
  //jika edit sukses
  if($hapus){
      echo "<script>
      alert('Hapus Data Berhasil!');
      document.location='edit.php';
      </script>";
  }

  else{
      echo "<script>
      alert('Hapus Data Gagal!');
      document.location='edit.php';
      </script>";
  }
}

$result = mysqli_query($conn, "SELECT * FROM list_harga " );
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
            <h2 class="fs-3 m-0">Menu Daftar Harga</h2>
          </div>
        </nav>
        <!-- navigation bar ends -->
     
        <!-- Dashboard Content Starts -->
        <div class="row">
            <!-- column starts -->
            <div class="col-md-11 mx-auto mt-5">
                <!-- card starts -->
            <div class="card">
        <div class="card-header bg-success text-light">
            Daftar Harga
        </div>
        
        <div class="card-body">
                         <!-- Button trigger modal -->
        <?php if($_SESSION["status"] == "Owner") : ?>
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahHarga">
            Tambah Harga
        </button>
        <?php endif; ?>
        <form method="POST" action="">
        <!-- Modal for tambah jenis kendaraan Starts -->
        <div class="modal fade" id="modalTambahHarga" data-bs-backdrop="static" data-bs-keyboard="false" 
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Tambah Jenis Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="tJenis" class="form-label">Jenis Kendaraan</label>
                <input type="text" class="form-control" name="tJenis" placeholder="Masukkan Jenis" maxlength="3" required>
                </div>
            <div class="mb-3">
                <label for="tHarga" class="form-label">Harga</label>
                <input type="text" pattern="[0-9]*" class="form-control" name="tHarga" placeholder="Masukkan Harga" 
                maxlength="8" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="tHar" >Konfirmasi</button>
            </div>
            </div>
        </div>
        </div>
        </form>
        <!-- modal for tambah jenis kendaraan ends -->

            <!-- table starts -->
            <div class="scrollable">
            <table class="table table-striped table-bordered">
            <tr>
                <th>No</th>
                <th>Jenis Kendaraan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php $i = 1; ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $row['jenis_kendaraan']; ?></td>
                <td><?= $row['harga']; ?></td>
                
                <td>

                  <!-- Modal Trigger -->
                <?php if($_SESSION["status"] == "Owner") : ?>
                <a href="#" class="btn btn-warning" 
                data-bs-toggle="modal" data-bs-target="#modalEditHarga<?= $i; ?>">Edit</a>
                <?php endif; ?>

                     <!-- Modal for edit harga Starts -->
            <div class="modal fade" id="modalEditHarga<?= $i; ?>" data-bs-backdrop="static" data-bs-keyboard="false" 
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="staticBackdropLabel">Edit Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="POST">
                  <input type="hidden" name="id_harga" value=" <?= $row['id_harga']?> ">
                <div class="mb-3">
                    <label for="tJenis" class="form-label"></label>
                    <input type="text" readonly class="form-control" name="eJenis" 
                    placeholder="Masukkan Jenis" maxlength="3" value="<?= $row['jenis_kendaraan']; ?>" required>
                    </div>
                <div class="mb-3">
                    <label for="tHarga" class="form-label">Harga</label>
                    <input type="text" pattern="[0-9]*" class="form-control" 
                    name="eHarga" placeholder="Masukkan Harga" 
                    maxlength="8" value="<?= $row['harga']; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="eHar" >Konfirmasi</button>
                </div>
                </div>
                </form>
            </div>
            </div>
            <!-- modal for edit harga ends -->

                 <!-- Modal for hapus harga Starts -->

                 <?php if($_SESSION["status"] == "Owner") : ?>
                 <a href="#" class="btn btn-danger" class="btn btn-warning" 
                data-bs-toggle="modal" data-bs-target="#modalHapusHarga<?= $i; ?>">Hapus</a>
                <?php endif; ?> 

                <div class="modal fade" id="modalHapusHarga<?= $i; ?>" data-bs-backdrop="static" data-bs-keyboard="false" 
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel">Hapus Data Harga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
              <form method="POST" action="">
              <input type="hidden" name="id_harga" value=" <?= $row['id_harga']?> ">

              <h5 class="text-center" >Apakah Anda yakin menghapus data ini? </br>
              <span class="text-danger"> Jenis: <?= $row['jenis_kendaraan']; ?></br> Harga: <?= $row['harga']; ?> </span>
              </h5>

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  <button type="submit" class="btn btn-danger" name="hHar" >Hapus</button>
              </div>
              </div>
                </div>
                </div>
              </form>
                </td>
            </tr>

            <!-- modal for hapus harga ends -->

              <?php $i++; ?>
              <?php endwhile; ?>
            
            </table>
            </div>
            <!-- table ends -->
        </div>
        <div class="card-footer bg-success">
        </div>
        </div>
        <!-- card ends -->
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
