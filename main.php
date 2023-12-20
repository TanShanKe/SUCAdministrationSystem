<?php
include 'config.php';

session_start(); // Start the session

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];

$sql = "SELECT name FROM student WHERE studentID  = '$userid'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name=$row['name'];
}   

include 'header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '';
</script>

<style>
    .font {
        font-family: "Verdana, Arial", cursive;
        font-size:18px;
		color:#320618;
    }
    .image {
        width: 100px;
        height: 100px;
    }
</style>

<div class="container" style="padding-top: 50px;">
    <center>
    <p>Logged in as student, <?php echo $name; ?></p>
  <div class="row" >
    <div class="col-sm" style="margin: 20px;">
        <a href="leave/viewLeave.php?page=1">
            <img src="images/leave.png" class="image"><br>
            <label class="form-label font">Incident & Funerary Leave Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="deferment/viewDeferment.php">
            <img src="images/deferment.png" class="image"><br>
            <label class="form-label font">Deferment/ Withdrawal Application</label>
        </a>
    </div>
    </div>
    <div class="row">
    <div class="col-sm" style="margin: 20px;">
        <a href="resumption/viewResumption.php">
            <img src="images/resumption.png" class="image"><br>
            <label class="form-label font">Resumption of Studies Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="document/viewDocument.php">
            <img src="images/document.png" class="image"><br>
            <label class="form-label font">Document Application</label>
        </a>
    </div>
    </div>
</center>
</div>

</body>
</html>