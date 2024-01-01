<?php
include '../config.php';
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];

$registrarSignature=null;
$registrarDecision=null;
$resumptionRegistrarSignature=null;
$registrarAcknowledge=null;
$defermentID=null;

$sql3 = "SELECT defermentID, registrarSignature, registrarDecision FROM deferment_record WHERE applicantID = '$userid' ORDER BY defermentID DESC LIMIT 1";

$result3 = $conn->query($sql3);
if ($result3->num_rows > 0) {
  while ($row = $result3->fetch_assoc()) {
    $registrarDecision=$row['registrarDecision'];
    $registrarSignature=$row['registrarSignature'];
    $defermentID=$row['defermentID'];
  }
}

$sql4 = "SELECT registrarAcknowledge, registrarSignature AS resumptionRegistrarSignature FROM resumption_of_studies_record WHERE defermentID = '$defermentID' AND applicantID = '$userid' ORDER BY resumptionID DESC LIMIT 1";

$result4 = $conn->query($sql4);
if ($result4->num_rows > 0) {
  while ($row = $result4->fetch_assoc()) {
    $registrarAcknowledge=$row['registrarAcknowledge'];
    $resumptionRegistrarSignature=$row['resumptionRegistrarSignature'];
  }
}

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM resumption_of_studies_record WHERE applicantID = '$userid'";
$result1 = $conn->query($sql1);
$years = array();
while ($row1 = $result1->fetch_assoc()) {
    $years[] = $row1['year'];
}
if(empty($years)){
  $years[] = date("Y");
}elseif(!empty($years)){
  $earliestYear = min($years);
  $years[] = $earliestYear - 1;
}
sort($years);

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) BETWEEN 10 AND 12 THEN 3 WHEN MONTH(MAX(applicationDate)) BETWEEN 1 AND 2 THEN 4 ELSE 1 END AS latestSem FROM resumption_of_studies_record WHERE applicantID = '$userid'";
$result2 = $conn->query($sql2);
while ($row2 = $result2->fetch_assoc()) {
  $default_year = $row2['latestYear'];
  $default_sem = $row2['latestSem'];
}
if($default_sem == 4){
  $default_year = $default_year-1;
  $default_sem = 3;
}

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function back() {
    location.href = '../main.php';
  }
  function applydisable(){
    var button = document.getElementById("applyBtn");
  <?php   
  if (($registrarSignature == 1 && $registrarDecision == 1 && $result4->num_rows == 0) || ($registrarSignature == 1 && $registrarDecision == 1)) { ?>
    button.disabled = false;
  <?php  } 
  if(($registrarSignature == 1 && $registrarDecision == 0)||($registrarSignature == 0 && $registrarDecision == null)||($registrarAcknowledge == 1 && $resumptionRegistrarSignature == 1)||is_null($defermentID)||($registrarAcknowledge == null && $resumptionRegistrarSignature == 0)){ ?>
    button.disabled = true;
  <?php }
  if($result4->num_rows == 0 && $registrarSignature == 1 && $registrarDecision == 1){ ?>
    button.disabled = false;
  <?php }
    ?> 
  }
</script>

<style>
th {
    background-color: #b8f4f4;
    border-style: solid;
    border-color: black;
}
table,td{
  border-style: solid;
  border-color: black;
}
</style>

<body onload='applydisable()'>
  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <div class="d-flex justify-content-center">
      <h3 style="margin-right: 20px">Resumption of Studies Application</h3>
      <button class="btn btn-primary" type="button" id = "applyBtn" onclick="location.href='applyResumption.php';">Apply</button>
      </div>
      <div class="row justify-content-center" style="margin: 20px;">
        <label for="year" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Year:</label>
        <select name="selected_year" id="selected_year" style="margin-right: 30px;">
        <?php foreach ($years as $year) : ?>
        <option value="<?php echo $year; ?>" <?php if (isset($_POST['selected_year']) && $_POST['selected_year'] == $year || (!isset($_POST['selected_year']) && $year == $default_year)) echo 'selected="selected"'; ?>>
            <?php echo $year; ?>
        </option>
        <?php endforeach; ?>
        </select>
        <label for="sem" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Sem:</label>
        <select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
          <option value="1" <?php if (isset($_POST['selected_sem']) && $_POST['selected_sem'] == '1' || (!isset($_POST['selected_sem']) && $default_sem == '1')) echo 'selected="selected"'; ?>>1</option>
          <option value="2" <?php if (isset($_POST['selected_sem']) && $_POST['selected_sem'] == '2' || (!isset($_POST['selected_sem']) && $default_sem == '2')) echo 'selected="selected"'; ?>>2</option>
          <option value="3" <?php if (isset($_POST['selected_sem']) && $_POST['selected_sem'] == '3' || (!isset($_POST['selected_sem']) && $default_sem == '3')) echo 'selected="selected"'; ?>>3</option>
        </select>
        <button name="check" type="submit" class="btn btn-outline-secondary" style="margin-left:20px;">Check</button>
      </div>
    </form>
  </div>
</div>

<div class="container-fluid" style="width: 90%;" >
    <div class="row">
    <table class="table "> 
        <tr>
          <th>No</th>
          <th>Register ID</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
        <?php

        if (!isset($_POST['selected_year']) || !isset($_POST['selected_sem'])) {
          // If user hasn't selected a year and semester, use the default values
          $rowNumber = 1;
          $selectedYear = $default_year;
          $selectedSem = $default_sem;
        }else{
          $rowNumber = 1;
          $selectedYear = $_POST['selected_year'];
          $selectedSem = $_POST['selected_sem'];
        }

        if ($selectedSem == 1) {
          $startMonth = 3; // March
          $endMonth = 5;   // May
          $sql = "SELECT resumptionID, applicationDate, aaroSignature, afoSignature, deanOrHeadSignature, registrarAcknowledge, registrarSignature FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid'ORDER BY resumptionID DESC";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT resumptionID, applicationDate, aaroSignature, afoSignature, deanOrHeadSignature, registrarAcknowledge, registrarSignature FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid'ORDER BY resumptionID DESC";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT resumptionID, applicationDate, aaroSignature, afoSignature, deanOrHeadSignature, registrarAcknowledge, registrarSignature FROM resumption_of_studies_record
          WHERE 
          (( YEAR(applicationDate) = '$selectedYear' AND 
            (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) 
          ) 
          OR 
            ( YEAR(applicationDate) = '$selectedYear'+1 AND 
            (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth) 
          ))
                AND
                applicantID = '$userid' ORDER BY resumptionID DESC";
      }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $resumptionID=$row['resumptionID']; 
            $applicationDate=$row['applicationDate'];
            $aaroSignature = $row['aaroSignature'];
            $afoSignature = $row['afoSignature'];
            $deanOrHeadSignature = $row['deanOrHeadSignature'];
            $registrarAcknowledge = $row['registrarAcknowledge'];
            $registrarSignature = $row['registrarSignature'];

                      
              if ($registrarSignature == 0) {
                  $status = 'Pending';
              }
               elseif ($registrarAcknowledge == 1) {
                  $status = 'Approved';
              } else {
                  $status = 'Disapproved';
              }
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $resumptionID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light"><a href="viewResumptionResult.php?resumptionID=<?php echo $resumptionID; ?>"><?php echo $status; ?></a></td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="4"><center>No application is done in this semester! </center></td></tr><?php
        }
        ?> 
    </table>
    </div>
    <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-top:15px; float: right;" onclick="back()";>Back</button>
  </body>
</html>
