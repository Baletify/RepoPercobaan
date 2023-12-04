<?php 

session_start();
//membatasi hak akses sebelum login
if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>;";
}

//membatasi hak akses berdasarkan user yang login
if( $_SESSION["status"] != "Owner"){

  echo "<script>alert('Anda Tidak punya akses!');window.location.replace('dashboard.php');</script>;";
}

require 'functions.php';

//Insert data pegawai
if(isset($_POST['tPeg'])){
  //cek data sudah berhasil ditambahkan atau belum
  if(insertPeg($_POST) > 0){

    echo "<script>
    alert('Data berhasil ditambahkan!');
    document.location = 'datapeg.php';
    </script>";
  }
  else{  
    echo "<script>
    alert('Gagal menambahkan data!');
    document.location = 'datapeg.php';
    </script>";
  }
}


if(isset($_POST['ePeg'])){

  //cek data sudah berhasil ditambahkan atau belum
  if(editPeg($_POST) > 0){

    echo "<script>
    alert('Data berhasil diubah');
    document.location = 'datapeg.php';
    </script>";
  }
  else{  
    echo "<script>
    alert('Gagal mengubah data!');
    document.location = 'datapeg.php';
    </script>";
  }
}

//hapus data pegawai
if (isset($_POST['hPeg'])){
  //memanggil kueri untuk hapus data
  $hapus = mysqli_query($conn, "DELETE FROM peg WHERE id_pegawai = '$_POST[id_pegawai]' ");
                                                
  //jika hapus sukses
  if($hapus){
      echo "<script>
      alert('Hapus Data Berhasil!');
      document.location='datapeg.php';
      </script>";
  }

  else{
      echo "<script>
      alert('Hapus Data Gagal!');
      document.location='datapeg.php';
      </script>";
  }
}


//menampilkan data dari tabel peg
$result = mysqli_query($conn, "SELECT * FROM peg " );


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
      .scroll{
        height: 250px;
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
            <h2 class="fs-3 m-0">Menu Data Pegawai</h2>
          </div>
        </nav>
        <!-- navigation bar ends -->

        <!-- Dashboard Content Starts -->
        <!-- row starts -->
        <div class="row">
            <!-- column starts -->
            <div class="col-md-11 mx-auto mt-5">
                <!-- card starts -->
            <div class="card">
        <div class="card-header bg-success text-light">
            Data Pegawai
        </div>
        <div class="card-body">

                    <!-- Button trigger modal -->
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPeg">
            Tambah Pegawai
        </button>

        <form method="POST" action="">
        <!-- Modal for tambah pegawai Starts -->
        <div class="modal fade" id="modalTambahPeg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="tnama" class="form-label">Nama</label>
                <input type="text" class="form-control" name="tNama" placeholder="Masukkan Nama" required>
                </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Alamat</label>
                <textarea class="form-control" name="tAlamat" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="tUsername" class="form-label">Username</label>
                <input type="text" class="form-control" name="tUsername" placeholder="Masukkan Username *maks 15 karakter" 
                maxlength="15" required>
                </div>
            
                <div class="mb-3">
                <label for="tPassword" class="form-label">Password</label>
                <input type="text" class="form-control" name="tPassword" placeholder="Masukkan Password *maks 15 karakter" 
                maxlength="15" required>
                </div>

                <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="tStatus" required>
                    <option></option>
                    <option value="Owner">Owner</option>
                    <option value="Pegawai">Pegawai</option>
                </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="tPeg" >Konfirmasi</button>
            </div>
            </div>
        </div>
        </div>
      </form>
        <!-- modal for tambah pegawai ends -->
        
            <!-- table starts -->
            <div class="scroll">
            <table class="table table-striped table-bordered">
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Username</th>
                <th>Password</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php $i = 1; ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['alamat']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['password']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                
                
                <!-- Button trigger modal for edit-->
                
                <a href="#" class="btn btn-warning" 
                data-bs-toggle="modal" data-bs-target="#modalEditPeg<?= $i; ?>">
                  Edit
                </a>

            <!-- Modal for edit pegawai Starts -->
                <div class="modal fade" id="modalEditPeg<?= $i; ?>" data-bs-backdrop="static" data-bs-keyboard="false" 
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel">Edit data pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                  <form method="POST" action="">

                    <input type="hidden" name="id_pegawai" value=" <?= $row['id_pegawai']?> ">
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="tnama" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="eNama" value="<?= $row['nama'] ?>" 
                        placeholder="Masukkan Nama" required>
                        </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Alamat</label>
                        <textarea class="form-control" name="eAlamat" 
                        rows="3" required><?= $row['alamat'] ?> </textarea>
                    </div>
                        <div class="mb-3">
                        <label for="tPassword" class="form-label">Password</label>
                        <input type="text" class="form-control" name="ePassword" value="<?= $row['password'] ?>"
                        placeholder="Masukkan Password *maks 15 karakter" 
                        maxlength="15" required>
                        </div>

                        <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="eStatus" required>
                            <option value="<?= $row['status']; ?>"><?= $row['status'] ?></option>
                            <option value="Owner">Owner</option>
                            <option value="Pegawai">Pegawai</option>
                        </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="ePeg" >Ubah</button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- modal for edit pegawai ends -->
            </form>
            
                <!-- Modal for hapus pegawai Starts -->
                
                    <a href='#' class='btn btn-danger' 
                data-bs-toggle='modal' data-bs-target='#modalHapusPeg<?= $i; ?>'>Hapus</a>
              
                      
                <div class="modal fade" id="modalHapusPeg<?= $i; ?>" data-bs-backdrop="static" data-bs-keyboard="false" 
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel">Hapus data pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="">

                    <input type="hidden" name="id_pegawai" value=" <?= $row['id_pegawai']?> ">
                    <div class="modal-body">

                    <h5 class="text-center" >Apakah Anda yakin menghapus data ini? </br>
                    <span class="text-danger"> nama: <?= $row['nama']; ?></br> Username: <?= $row['username']; ?> </span>
                    </h5>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger" name="hPeg" >Hapus</button>
                    </div>
                    </div>
                </div>
                </div>
                </div>
                <!-- modal for hapus pegawai ends -->
                </td>
            </tr>
            </form>
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
