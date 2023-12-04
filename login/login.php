<?php 
session_start();

// if(isset($_SESSION["login"])){
//   header("Location: ../dashboard.php");
// }

if(isset($_POST["login"])){
  $username = $_POST["username"];
  $password = $_POST["password"];
  require '../functions.php';
  $result = mysqli_query($conn, "SELECT * FROM peg WHERE username = '$username'");

  //cek username
  if(mysqli_num_rows($result) === 1){

    //cek password
    $row = mysqli_fetch_assoc($result);
    if($password === $row["password"]){

      //set session
      $_SESSION["login"]      = true;
      $_SESSION["id_pegawai"] = $row["id_pegawai"];
      $_SESSION["nama"]       = $row["nama"];
      $_SESSION["status"]     = $row["status"];

    echo "<script>alert('Login Berhasil');window.location.replace('../dashboard.php');</script>;";
    }
  } else{
    echo "<script>alert('Login gagal');window.location.replace('login.php');</script>;";
  }
}



?>
<!DOCTYPE html>

<head lang="en">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SI2P</title>

  
  <!--My Style-->
  <link rel="stylesheet" href="style.css">
  
</head>
<body>
  <section>
  <div class="form-box">
    <div class="form-content"> 
      <div class="title">APLIKASI PENCATATAN DAN PENDATAAN PENCUCIAN SEPEDA MOTOR SALSA</div>
      <form action="" method="post">
        <h2>LOGIN USER</h2>
        <div class="input-box">
          <ion-icon name="arrow-forward-circle"></ion-icon>
          <input type="text" name="username" id="username" required>
          <label for="username">Username</label>
        </div>
        <div class="input-box">
          <ion-icon name="lock-closed-outline"></ion-icon>
          <input type="password" name="password" id="password" required>
          <label for="password">Password</label>
        </div>
        <button type="submit" name="login">login</button>
      </form>
    </div>
  </div>
  </section>

  <!--Ion Icons-->

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>