<?php
include '../config.php';
session_start();
$userid = $_SESSION['userid'];

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM resumption_of_studies_record WHERE applicantID = '$userid'";
$result1 = $conn->query($sql1);
$years = array();
while ($row1 = $result1->fetch_assoc()) {
    $years[] = $row1['year'];
}
sort($years);

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) IN (10, 11, 12, 1, 2) THEN 3 ELSE 1 END AS latestSem FROM resumption_of_studies_record WHERE applicantID = '$userid'";
$result2 = $conn->query($sql2);
while ($row2 = $result2->fetch_assoc()) {
  $default_year = $row2['latestYear'];
  $default_sem = $row2['latestSem'];
}

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
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

<div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <div class="d-flex justify-content-center">
      <h3 style="margin-right: 20px">Resumption of Studies Application</h3>
      <button class="btn btn-primary" type="button" onclick="location.href='applyResumption.php';">Register</button>
      </div>
      <div class="row justify-content-center" style="margin: 20px;">
        <label for="year" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Year:</label>
        <select name="selected_year" id="selected_year" style="margin-right: 30px;">
        <option value="">Year</option>
        <?php foreach ($years as $year) : ?>
        <option value="<?php echo $year; ?>">
            <?php echo $year; ?>
        </option>
        <?php endforeach; ?>
        </select>
        <label for="sem" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Sem:</label>
        <select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
        <option value="">Sem</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        </select>
        <button name="check" type="submit" class="btn btn-secondary" style="margin-left:20px;">Check</button>
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
          $sql = "SELECT resumptionID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid'ORDER BY applicationDate DESC";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT resumptionID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature  FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid'ORDER BY applicationDate DESC";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT resumptionID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature  FROM resumption_of_studies_record
                WHERE YEAR(applicationDate) = '$selectedYear' AND (
                  (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR
                  (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth)
                )AND
                applicantID = '$userid' ORDER BY applicationDate DESC";
      }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $resumptionID=$row['resumptionID']; 
            $applicationDate=$row['applicationDate'];
            $aaroAcknowledge = $row['aaroAcknowledge'];
            $aaroSignature = $row['aaroSignature'];
            $afoAcknowledge = $row['afoAcknowledge'];
            $afoSignature = $row['afoSignature'];
            $deanOrHeadAcknowledge = $row['deanOrHeadAcknowledge'];
            $deanOrHeadSignature = $row['deanOrHeadSignature'];
                      
              if ($aaroSignature == 0 || $afoSignature == 0 || $deanOrHeadSignature == 0) {
                  $status = 'Pending';
              }
               elseif ($aaroAcknowledge == 1 && $afoAcknowledge == 1 && $deanOrHeadAcknowledge == 1) {
                  $status = 'Approved';
              } else {
                  $status = 'Disapproved';
              }
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $resumptionID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light"><?php if ($status === 'Approved' || $status === 'Disapproved'): ?>
          <a href="viewResumptionResult.php?resumptionID=<?php echo $resumptionID; ?>"><?php echo $status; ?></a>
          <?php else: ?><?php echo $status; ?><?php endif; ?></td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="4"><center>No application is done in this semester!</center></td></tr><?php
        }
        ?> 
    </table>
    </div>
  </div>

  </body>
</html>
