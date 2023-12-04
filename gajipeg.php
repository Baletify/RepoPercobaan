<?php 

session_start();

if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>";
}
require 'functions.php';

if(isset($_POST['tGaji'])){
  //cek data sudah berhasil ditambahkan atau belum
  if(insertGaji($_POST) > 0){

    echo "<script>
    alert('Data berhasil ditambahkan!');
    document.location = 'gajipeg.php';
    </script>";
  }
  else{  
    echo "<script>
    alert('Gagal menambahkan data!');
    document.location = 'gajipeg.php';
    </script>";
  }
}

if (isset($_POST['hGaji'])){
  //memanggil kueri untuk hapus data
  $hapus = mysqli_query($conn, "DELETE FROM gaji_pegawai WHERE id_gaji = '$_POST[hID]' ");
                                                
  //jika hapus sukses
  if($hapus){
      echo "<script>
      alert('Hapus Data Berhasil!');
      document.location='gajipeg.php';
      </script>";
  }

  else{
      echo "<script>
      alert('Hapus Data Gagal!');
      document.location='gajipeg.php';
      </script>";
  }
}


// data pagination
$jumlahDataPerHalaman = 5;

$ambilDataPemasukan = mysqli_query($conn, "SELECT * FROM gaji_pegawai");
$jumlahData         = mysqli_num_rows($ambilDataPemasukan);
$jumlahHalaman      = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif       = (isset($_GET['halaman']) ? $_GET['halaman'] : 1);
$awalData           = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

//membatasi jumlah pagination
$jumlahLink = 1;

$start_page = ($halamanAktif > $jumlahLink) ? $halamanAktif - $jumlahLink : 1;
$end_page = ($halamanAktif < ($jumlahHalaman - $jumlahLink)) ? $halamanAktif + $jumlahLink : $jumlahHalaman;

$result = mysqli_query($conn, "SELECT * FROM gaji_pegawai ORDER BY id_gaji DESC LIMIT $awalData, $jumlahDataPerHalaman");

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
            <h2 class="fs-3 m-0">Menu Gaji Pegawai</h2>
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
            Gaji Pegawai
        </div>
        
        <div class="card-body">
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalFilterGaji">
            Filter
        </button>

        <?php if($_SESSION["status"] == "Owner") : ?>
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalTambahGaji">
            Tambah Data Gaji
        </button>
        <?php endif; ?>

        <form method="POST" action="tampil_gaji.php">
        <!-- Modal for filter Starts -->
        <div class="modal fade" id="modalFilterGaji" data-bs-backdrop="static" data-bs-keyboard="false" 
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="Nama" class="form-label">Nama Pegawai</label>
                
                <select name="fPilihPeg">
                  <option>--- Pilih Pegawai ---</option>
                  
                  <?php 
                  $data_peg = mysqli_query($conn, "SELECT * FROM peg");
                  while($row = mysqli_fetch_assoc($data_peg)) :
                    echo "<option value= $row[id_pegawai]>$row[nama]</option>
                         ";
                  
                  ?>
                  
                  <?php endwhile; ?>
                </select>
                </div>

                <div class="mb-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awalf" class="form-control" >
                </div>

                <div class="mb-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhirf" class="form-control" >
                </div>
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="fGaji" >
                  Konfirmasi
                </button>
            </div>
            </div>
        </div>
        </div>
      </form>
      <!-- modal for Filter ends -->

      <form method="POST" action="">
        <!-- Modal for tambah pemasukan Starts -->
        <div class="modal fade" id="modalTambahGaji" data-bs-backdrop="static" data-bs-keyboard="false" 
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="Nama" class="form-label">Nama Pegawai</label>
                
                <select name="tPilihPeg" required>
                 
                  
                  <?php 
                  $data_peg = mysqli_query($conn, "SELECT * FROM peg");
                  while($row = mysqli_fetch_assoc($data_peg)) :
                    echo "<option value= $row[id_pegawai]>$row[nama]</option>
                         ";
                  
                  ?>
                  
                  <?php endwhile; ?>
                </select>
                </div>

                <div class="mb-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awalt" class="form-control" required >
                </div>

                <div class="mb-3">
                <label for="tanggal_akhir" class="form-label" required>Tanggal Akhir</label>
                <input type="date" name="tgl_akhirt" class="form-control" required>
                </div>
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="tGaji" >
                  Konfirmasi
                </button>
            </div>
            </div>
        </div>
        </div>
      </form>
        <!-- modal for tambah gaji pegawai ends -->

            <!-- table starts -->
           
            <table class="table table-striped table-bordered">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Total Pemasukan</th>
                <th>Besar Gaji</th>
                <th>Aksi</th>
                
                
            </tr>

            
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <?php $no = $awalData += 1; ?>
            <tr>
              <td><?= $no ?></td>
              <td><?= $row['tgl_awal']; ?> - <?= $row['tgl_akhir']; ?></td>
              <td><?= $row['nama_penerima']; ?></td>
              <td>Rp. <?= number_format($row['total_pemasukan'],0,',','.'); ?></td>
              <td>Rp. <?= number_format($row['besar_gaji'],0,',','.'); ?> </td>

              <td>

                  <!-- Modal Trigger -->
                <?php if($_SESSION["status"] == "Owner") : ?>
                <a href="#" class="btn btn-danger" 
                data-bs-toggle="modal" data-bs-target="#modalHapusGaji<?= $no; ?>">Hapus</a>
                <?php endif; ?>

                <form method="POST">
            <!-- Modal for hapus gaji Starts -->
            <div class="modal fade" id="modalHapusGaji<?= $no; ?>" data-bs-backdrop="static" data-bs-keyboard="false" 
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="staticBackdropLabel">Hapus Data Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" name="hID" value="<?= $row['id_gaji']; ?>">
                <h5 class="text-center" >Anda yakin menghapus data ini?</h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="hGaji" >Konfirmasi</button>
                </div>
                </div>
            </div>
            </div>
            
            <!-- modal for hapus gaji ends -->
              </td>
              </form>
            </tr>
            <?php endwhile; ?>
            

            
            
            </table>
            <!-- table ends -->
        </div>

        <div class="mx-3 justify-content-end d-flex">
          <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php if ($halamanAktif > 1) : ?>
            <li class="page-item">
              <a class="page-link" href="?halaman=<?= $halamanAktif - 1?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <?php endif; ?>

            <?php for($i = $start_page; $i <= $end_page; $i++ ) : ?>
            <?php if($i == $halamanAktif) : ?>
            <li class="page-item active"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
            <?php else : ?>
              <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
            <?php endif; ?>
            <?php endfor; ?>

            <?php if($halamanAktif < $jumlahHalaman) : ?>
            <li class="page-item">
              <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
            <?php endif; ?>


          </ul>
        </nav>
          </div>

        <div class="card-footer bg-success">
        </div>
        </div>
        <!-- card ends -->
            </div>
            <!-- columns ends -->
        </div>
        <!-- row ends -->

      </div>
    <!-- Dashboard Content Ends -->
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
