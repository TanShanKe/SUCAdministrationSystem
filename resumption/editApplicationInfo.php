<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only aaro can access this page
$allowedPositions = ["aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$resumptionID = $_GET['resumptionID'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
$sql1 = "SELECT student.name AS name, student.batchNo AS batchNo, applicantID, applicationDate, yearOfResumption, semOfResumption, yearOfDeferment, semOfDeferment FROM resumption_of_studies_record left join student on resumption_of_studies_record.applicantID=student.studentID WHERE resumptionID = '$resumptionID'";

$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $applicationDate=$row['applicationDate'];
    $applicantID=$row['applicantID'];
    $applicationName=$row['name'];
    $batchNo=$row['batchNo'];
    $yearOfResumption=$row['yearOfResumption'];
    $semOfResumption=$row['semOfResumption'];
    $yearOfDeferment=$row['yearOfDeferment'];
    $semOfDeferment=$row['semOfDeferment'];
  }
}        
 
  // Edit the applicatiion details
  if(isset($_POST['submit'])){
    $resumptionID = $_POST['resumptionID'];

    $yearOfResumption=$_POST['yearOfResumption'];
    $semOfResumption=$_POST['semOfResumption']; 

    $sql = "UPDATE resumption_of_studies_record 
    SET yearOfResumption = '$yearOfResumption',
        semOfResumption = '$semOfResumption'
    WHERE resumptionID = '$resumptionID'";
    $result=$conn->query($sql);

    if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Successfully submitted!");'; 
      echo 'window.location = "reviewResumption.php?resumptionID=' . $resumptionID . '&status=Review";';
      echo '</script>';
    } else {
        echo "Error: " . $conn->error;
    }
  }

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<style>
.thReview {
    background-color: #b8f4f4;
    border-style: solid;
    border-color: black;
    width: 20%
}
.thInfo {
    background-color: #D3D3D3;
    border-style: solid;
    border-color: black;
    width: 20%
}
table,td{
  border-style: solid;
  border-color: black;
}
</style>

<script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'reviewResumption.php?resumptionID=<?php echo $resumptionID; ?>&status=Review';
    }
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Resumption of Studies Application</h3>
  </div>
  <form  action="editApplicationInfo.php" method="post" enctype="multipart/form-data">
    <div class="row" style="margin:40px; margin-top:15px">
    <label for="" class="form-label">Application Details</label>
    <table class="table">  
        <tr>
          <th class="thInfo">Application ID</th><td class="table-light"><?php echo $resumptionID; ?></td>
          <th class="thInfo">Application Date</th><td class="table-light"><?php echo $applicationDate; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Student Name</th><td class="table-light"><?php echo $applicationName; ?></td>
          <th class="thInfo">Student ID</th><td class="table-light"><?php echo $applicantID; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Student Batch No</th><td class="table-light " colspan="3"><?php echo $batchNo; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Year of Deferment</th><td class="table-light"><?php echo $yearOfDeferment; ?></td>
          <th class="thInfo">Sem of Deferment</th><td class="table-light"><?php echo $semOfDeferment; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Year of Resumption</th><td class="table-light"><input name="yearOfResumption" id="yearOfResumption" style="margin-right: 50px;" value="<?php echo $yearOfResumption ?>" required></td>
          <th class="thInfo">Sem of Resumption</th><td class="table-light"><input name="semOfResumption" id="semOfResumption" style="margin-right: 50px;" value="<?php echo $semOfResumption ?>" required></td>
        </tr>
    </table>
    <input type="hidden" name="resumptionID" value="<?php echo $resumptionID; ?>">
      </div>
      <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px; float:right; margin-right:40px;";>Submit</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right; margin-right:20px" onclick="confirmCancel()";>Cancel</button>
</form>
  </body>
</html>