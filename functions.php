<?php

//koneksi ke database

$conn = mysqli_connect("localhost", "root", "", "pbl");

//menampilkan data
function query($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function insertPeg($data){

    global $conn;
    
    //Ambil data setelah tombol tambah ditekan
    $nama = htmlspecialchars($data['tNama']);
    $alamat = htmlspecialchars($data['tAlamat']);
    $username = htmlspecialchars($data['tUsername']);
    $password = htmlspecialchars($data['tPassword']);
    $status = htmlspecialchars($data['tStatus']);

    //cek username apakah sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM peg WHERE username = '$username'");

    if(mysqli_fetch_assoc($result) ){
        echo "<script>
        alert('Username sudah ada!');
        </script>";

        return false;
    }

    //kueri untuk menambah data
    $query = "INSERT INTO peg VALUES ('', '$nama', '$alamat', '$username', '$password', '$status')";

    mysqli_query($conn, $query);

    //cek apakah tambah data berhasil
    return mysqli_affected_rows($conn);

}

// edit pegawai
function editPeg($data){
    
    global $conn;
    
    //Ambil data setelah tombol tambah ditekan
    $id = $data['id_pegawai'];
    $nama = htmlspecialchars($data['eNama']);
    $alamat = htmlspecialchars($data['eAlamat']);
    $password = htmlspecialchars($data['ePassword']);
    $status = htmlspecialchars($data['eStatus']);
    
    //kueri untuk menambah data
    $query = "UPDATE peg SET nama = '$nama',
                             alamat = '$alamat',
                             password = '$password',
                             status = '$status'
                             WHERE id_pegawai = $id
        ";
    mysqli_query($conn, $query);


    //cek apakah tambah data berhasil
    return mysqli_affected_rows($conn);

}

//Insert Harga
function insertHarga($data){

    global $conn;
    
    //Ambil data setelah tombol tambah ditekan
    $jenis = $data['tJenis'];
    $harga = $data['tHarga'];

    //cek username apakah sudah ada atau belum
    $result = mysqli_query($conn, "SELECT jenis_kendaraan FROM list_harga WHERE jenis_kendaraan = '$jenis'");

    if(mysqli_fetch_assoc($result) ){
        echo "<script>
        alert('Jenis ini sudah ada!');
        </script>";

        return false;
    }

    //kueri untuk menambah data
    $query = "INSERT INTO list_harga VALUES ('', '$jenis', '$harga')";

    mysqli_query($conn, $query);

    //cek apakah tambah data berhasil
    return mysqli_affected_rows($conn);

}

//edit harga
function editHarga($data){
    global $conn;
    
    //Ambil data setelah tombol edit ditekan
    $id = $data['id_harga'];
    $harga = $data['eHarga'];

    //kueri untuk mengedit
    $query = "UPDATE list_harga SET harga = '$harga' WHERE id_harga = '$id'";

    mysqli_query($conn, $query);

    //cek apakah tambah data berhasil
    return mysqli_affected_rows($conn);

}

//Insert pemasukan
function insertPemasukan($data){

    global $conn;
    
    // $ambilData = "SELECT * FROM list_harga WHERE id_harga = '$_POST[idharga]'";
    // $ambilHarga = mysqli_query($conn, $ambilData);
    // $row = mysqli_fetch_assoc($ambilHarga);
    // $harga = $row['harga'];
    // $jk = $row['j_kend'];
    // $idpeg = $data ['nID'];
    // $idharga = $data['idharga'];
    // $nama = $data['nPem'];

    //Ambil data setelah tombol edit ditekan

    $resultJenis = mysqli_query($conn, "SELECT jenis_kendaraan FROM list_harga WHERE id_harga = '$_POST[tKend]'");
    $rowJenis = mysqli_fetch_assoc($resultJenis);
    $ambilJenis = $rowJenis['jenis_kendaraan'];

    $resultHarga = mysqli_query($conn, "SELECT harga FROM list_harga WHERE id_harga = '$_POST[tKend]'");
    $rowHarga = mysqli_fetch_assoc($resultHarga);
    $ambilHarga = $rowHarga['harga'];
    //kueri untuk mengedit
    $query = "INSERT INTO pemasukan SET 
             nama_input = '$_POST[tPeg]',
             jenis_input = '$ambilJenis',
             harga_input = '$ambilHarga',
             id_pegawai = '$_POST[tID]',
             id_harga = '$_POST[tKend]'
             ";

    mysqli_query($conn, $query);

    //cek apakah tambah data berhasil
    return mysqli_affected_rows($conn);
}


function editPemasukan($data){
    global $conn;

    $resultJenis = mysqli_query($conn, "SELECT jenis_kendaraan FROM list_harga WHERE id_harga = '$_POST[eKend]'");
    $rowJenis = mysqli_fetch_assoc($resultJenis);
    $ambilJenis = $rowJenis['jenis_kendaraan'];

    $resultHarga = mysqli_query($conn, "SELECT harga FROM list_harga WHERE id_harga = '$_POST[eKend]'");
    $rowHarga = mysqli_fetch_assoc($resultHarga);
    $ambilHarga = $rowHarga['harga'];

    $query = "UPDATE pemasukan SET  

             jenis_input = '$ambilJenis',
             harga_input = '$ambilHarga',
             id_harga = '$_POST[eKend]'

             WHERE id_pemasukan = '$_POST[editIDpem]'
             ";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}


function insertGaji($data){
    global $conn;


    $gaji_tgl_awal   = strip_tags($data['tgl_awalt']);
    $gaji_tgl_akhir  = strip_tags($data['tgl_akhirt']);

    $resultNama = mysqli_query($conn, "SELECT nama FROM peg WHERE id_pegawai = '$_POST[tPilihPeg]' ");
    $rowNama = mysqli_fetch_assoc($resultNama);
    $ambilNama = $rowNama['nama'];

    $queryTotal = mysqli_query($conn, "SELECT SUM(CAST(harga_input AS DECIMAL)) AS total FROM pemasukan
                                WHERE nama_input = '$ambilNama' AND tgl_input 
                                BETWEEN ' $gaji_tgl_awal' AND DATE_ADD('$gaji_tgl_akhir', INTERVAL 1 DAY)");
    $rowTotal = mysqli_fetch_assoc($queryTotal);
    $total = $rowTotal['total'];
    $gajiPeg = $total / 2;


    $query = "INSERT INTO gaji_pegawai SET
              nama_penerima = '$ambilNama',
              total_pemasukan = '$total',
              besar_gaji = '$gajiPeg',
              tgl_awal = '$gaji_tgl_awal',
              tgl_akhir = '$gaji_tgl_akhir'
             ";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}




?>
