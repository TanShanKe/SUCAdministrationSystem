<?php
include 'config.php';

session_start();

if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

$allowedPositions = ["registrar"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];
$position = $_SESSION['position'];


$sql = "SELECT name FROM administrator WHERE administratorID  = '$userid'";


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
    <p>Logged in as <?php echo $position; ?>, <?php echo $name; ?></p>
    <div class="row">
    <div class="col-sm" style="margin: 20px;">
        <a href="deferment/viewDefermentApplied.php">
            <img src="images/deferment.png" class="image"><br>
            <label class="form-label font">Deferment/ Withdrawal Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="resumption/viewResumptionApplied.php">
            <img src="images/resumption.png" class="image"><br>
            <label class="form-label font">Resumption of Studies Application</label>
        </a>
    </div>
    </div>
</center>
</div>

</body>
</html>