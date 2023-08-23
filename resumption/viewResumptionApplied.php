<?php
include '../config.php';
session_start();
$userid = $_SESSION['userid'];

$sql = "SELECT * FROM users WHERE userid = '$userid'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $position = $row['position'];
}

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM resumption_of_studies_record";
$result1 = $conn->query($sql1);
$years = array();
while ($row = $result1->fetch_assoc()) {
    $years[] = $row['year'];
}
sort($years);

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) IN (10, 11, 12, 1, 2) THEN 3 ELSE 1 END AS latestSem FROM resumption_of_studies_record";
$result2 = $conn->query($sql2);
while ($row = $result2->fetch_assoc()) {
  $default_year = $row['latestYear'];
  $default_sem = $row['latestSem'];
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
        <label for="status" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Status:</label>
        <select name="selected_status" id="selected_status" style="margin-right: 30px;">
          <option value="">Status</option>
          <option value="approval">Approval</option>
          <option value="completed">Completed</option>
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
          <th>Applicant ID</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
        <?php

        if (!isset($_POST['selected_year']) || !isset($_POST['selected_sem']) || !isset($_POST['selected_status'])) {
          $rowNumber = 1;
          $selectedYear = $default_year;
          $selectedSem = $default_sem;
          $selectedStatus = '';
        }else{
          $rowNumber = 1;
          $selectedYear = $_POST['selected_year'];
          $selectedSem = $_POST['selected_sem'];
          $selectedStatus = $_POST['selected_status'];
        }

        if ($selectedSem == 1) {
          $startMonth = 3; // March
          $endMonth = 5;   // May
          $sql = "SELECT resumptionID, applicationDate, applicantID, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature, deanOrHeadID  FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT resumptionID, applicationDate, applicantID, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature, deanOrHeadID   FROM resumption_of_studies_record
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT resumptionID, applicationDate, applicantID, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature, deanOrHeadID  FROM resumption_of_studies_record
                WHERE YEAR(applicationDate) = '$selectedYear' AND (
                  (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR
                  (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth))";
      }

      if ($selectedStatus == 'approval' && $position == 'deanOrHod') {
        $sql .= " AND deanOrHeadSignature = 0";
      } elseif($selectedStatus == 'approval' && $position == 'aaro'){
        $sql .= " AND aaroSignature = 0";
      } elseif($selectedStatus == 'approval' && $position == 'afo'){
        $sql .= " AND afoSignature = 0";
      } elseif($selectedStatus == 'completed' && $position == 'deanOrHod'){
        $sql .= " AND deanOrHeadSignature = 1";
      } elseif($selectedStatus == 'completed' && $position == 'aaro'){
        $sql .= " AND aaroSignature = 1";
      } elseif($selectedStatus == 'completed' && $position == 'afo'){
        $sql .= " AND afoSignature = 1";
      }

      $sql .= " ORDER BY applicationDate DESC";
      
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $resumptionID=$row['resumptionID']; 
            $applicationDate=$row['applicationDate'];
            $applicantID=$row['applicantID'];
            $aaroAcknowledge = $row['aaroAcknowledge'];
            $aaroSignature = $row['aaroSignature'];
            $afoAcknowledge = $row['afoAcknowledge'];
            $afoSignature = $row['afoSignature'];
            $deanOrHeadAcknowledge = $row['deanOrHeadAcknowledge'];
            $deanOrHeadSignature = $row['deanOrHeadSignature'];
            $deanOrHeadID = $row['deanOrHeadID'];

              if($position == 'deanOrHod'){
                if($deanOrHeadSignature == 0){
                  $status = 'Approval';
                }else{
                  $status = 'Completed';
                }
              } elseif($position =='aaro'){
                if($aaroSignature == 0){
                  $status = 'Approval';
                }else{
                  $status = 'Completed';
                }
              } elseif($position =='afo'){
                if($afoSignature == 0){
                  $status = 'Approval';
                }else{
                  $status = 'Completed';
                }
              }
                    
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $resumptionID; ?></td>
          <td class="table-light"><?php echo $applicantID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light">
          <a href="reviewResumption.php?resumptionID=<?php echo $resumptionID; ?>&status=<?php echo $status; ?>"><?php echo $status; ?></a>
          </td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="5"><center>No application is done in this semester!</center></td></tr><?php
        }
        ?> 
    </table>
    </div>
  </div>

  </body>
</html>

