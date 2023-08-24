<?php
session_start();
include 'config.php';
echo "<body style='background-color:#E5F5F8'>";

if (isset($_POST['login'])) {
  $userid = $_POST['userid'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE userid = '$userid'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stored_password = $row['password'];
      $position = $row['position'];

      if ($password === $stored_password) {
          $_SESSION['userid'] = $userid;
          $_SESSION['position'] = $position;
          if($position == 'student'){
            header("Location: main.php");
          }elseif($position == 'aaro' || $position == 'afo' || $position == 'deanOrHod'){
            header("Location: adminMain.php");
          }
          exit;
      } else {
          echo "Incorrect password!";
      }
  } else {
      echo "User not found!";
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>SUC Administration Sytem</title>
  </head>
  <body>
    <nav class="navbar navbar-light" style="background-color: #B7C6EE;">
      <div class="container-fluid">
        <a class="navbar-brand" style="font-size: 150%">SUC Administration System</a>
      </div>
    </nav>
    <div class="container mt-5">
        <div class="login-box mx-auto bg-white p-4 rounded shadow-sm" style="max-width: 300px; margin-top: 60px;">
            <h2 class="text-center mb-4">Login</h2>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="userid">User Id:</label>
                    <input type="text" class="form-control" id="userid" name="userid" placeholder="Enter your User ID" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your Password" required>
                </div>
                <button name="login" type="submit" class="btn btn-primary btn-block mx-auto" style="width:50%;">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
