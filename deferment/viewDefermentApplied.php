<?php
include '../config.php';
session_start();

$allowedPositions = ["deanOrHod", "aaro", "afo", "library", "sao", "iso", "sro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  
  exit();
}

$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

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
  function back() {
    location.href = '../adminMain.php';
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

  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <div class="d-flex justify-content-center">
      <h3 style="margin-right: 20px">Deferment / Withdrawal of Studies Application</h3>
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
        <label for="status" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Status:</label>
        <select name="selected_status" id="selected_status" style="margin-right: 30px;">
          <option value="">All</option>
          <option value="approval" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'review') echo 'selected="selected"'; ?>>Review</option>
          <option value="completed" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'completed') echo 'selected="selected"'; ?>>Completed</option>
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
          <?php if($position == 'sao'){ ?>
            <th>Officer Status</th>
            <th>Counseling Unit Status</th>
          <?php }
          elseif($position == 'aaro') { ?>
            <th>Officer Status</th>
            <th>Registrar Status</th>
          <?php }
          else { ?>
            <th>Status</th>
          <?php } ?>
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
          $sql = "SELECT defermentID, applicationDate, applicantID, isoSignature, saoSignature, counselingSignature, sroSignature, librarySignature, afoSignature, hodSignature, aaroSignature, registrarSignature, student.type FROM deferment_record LEFT JOIN student ON deferment_record.applicantID=student.studentID
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT defermentID, applicationDate, applicantID, isoSignature, saoSignature, counselingSignature, sroSignature, librarySignature, afoSignature, hodSignature, aaroSignature, registrarSignature, student.type FROM deferment_record LEFT JOIN student ON deferment_record.applicantID=student.studentID
          WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT defermentID, applicationDate, applicantID, isoSignature, saoSignature, counselingSignature, sroSignature, librarySignature, afoSignature, hodSignature, aaroSignature, registrarSignature, student.type FROM deferment_record LEFT JOIN student ON deferment_record.applicantID=student.studentID
                WHERE YEAR(applicationDate) = '$selectedYear' AND (
                  (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR
                  (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth))";
      }

      if ($position == 'iso') {
        $sql .= " AND student.type = 'International' ";
        if ($selectedStatus == 'review') {
          $sql .= " AND isoSignature = 0 ";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND isoSignature = 1";
        }
      } elseif ($position == 'sao') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') ";
        if ($selectedStatus == 'review') {
          $sql .= " AND saoSignature = 0 || counselingSignature = 0";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND saoSignature = 1  || counselingSignature = 1";
        }
      } elseif ($position == 'sro') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') AND saoSignature = 1 AND counselingSignature = 1 ";
        if ($selectedStatus == 'review') {
          $sql .= " AND sroSignature = 0";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND sroSignature = 1";
        }
      } elseif ($position == 'library') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') AND saoSignature = 1 AND counselingSignature = 1 AND sroSignature = 1";
        if ($selectedStatus == 'review') {
          $sql .= " AND librarySignature = 0 ";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND librarySignature = 1";
        }
      } elseif ($position == 'afo') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') AND saoSignature = 1 AND counselingSignature = 1 AND sroSignature = 1 AND librarySignature ";
        if ($selectedStatus == 'review') {
          $sql .= " AND afoSignature = 0 ";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND afoSignature = 1";
        }
      } elseif ($position == 'deanOrHod') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') AND saoSignature = 1 AND counselingSignature = 1 AND sroSignature = 1 AND librarySignature AND afoSignature ";
        if ($selectedStatus == 'review') {
          $sql .= " AND hodSignature = 0 ";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND hodSignature = 1";
        }
      } elseif ($position == 'aaro') {
        $sql .= " AND  (isoSignature = 1 AND student.type = 'International') || (isoSignature = 0 AND student.type = 'Local') AND saoSignature = 1 AND counselingSignature = 1 AND sroSignature = 1 AND librarySignature AND afoSignature AND hodSignature = 1";
        if ($selectedStatus == 'review') {
          $sql .= " AND aaroSignature = 0 || registrarSignature = 0";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND aaroSignature = 1 || registrarSignature = 1";
        }
      }

      $sql .= " ORDER BY applicationDate DESC";
      
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $defermentID=$row['defermentID']; 
            $applicationDate=$row['applicationDate'];
            $applicantID=$row['applicantID'];
            $isoSignature = $row['isoSignature'];
            $saoSignature = $row['saoSignature'];
            $counselingSignature = $row['counselingSignature'];
            $sroSignature = $row['sroSignature'];
            $librarySignature = $row['librarySignature'];
            $afoSignature = $row['afoSignature'];
            $hodSignature = $row['hodSignature'];
            $aaroSignature = $row['aaroSignature'];
            $registrarSignature = $row['registrarSignature'];

              if($position == 'iso'){
                if($isoSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
              }elseif($position == 'sao'){
                if($saoSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
                if($counselingSignature == 0){
                  $status2 = 'Review';
                }else{
                  $status2 = 'Completed';
                }
              }elseif($position == 'sro'){
                if($sroSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
              }elseif($position == 'library'){
                if($librarySignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
              }elseif($position =='afo'){
                if($afoSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
              }elseif($position == 'deanOrHod'){
                if($hodSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
              } elseif($position =='aaro'){
                if($aaroSignature == 0){
                  $status = 'Review';
                }else{
                  $status = 'Completed';
                }
                if($registrarSignature == 0){
                  $status2 = 'Review';
                }else{
                  $status2 = 'Completed';
                }
              }
                    
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $defermentID; ?></td>
          <td class="table-light"><?php echo $applicantID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <?php if($position == 'aaro' || $position == 'sao') { ?>
            <td class="table-light">
            <a href="reviewDeferment.php?defermentID=<?php echo $defermentID; ?>&status=<?php echo $status; ?>"><?php echo $status; ?></a>
            </td>
            <td class="table-light">
            <a href="reviewDeferment.php?defermentID=<?php echo $defermentID; ?>&status=<?php echo $status2; ?>"><?php echo $status2; ?></a>
            </td>
          <?php }
          else { ?>
            <td class="table-light">
            <a href="reviewDeferment.php?defermentID=<?php echo $defermentID; ?>&status=<?php echo $status; ?>"><?php echo $status; ?></a>
            </td>
          <?php } ?>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" <?php if($position == 'aaro' || $position == 'sao'){ ?>colspan="6" <?php } else { ?> colspan="5" <?php } ?>><center>No application is done in this semester!</center></td></tr><?php
        }
        ?> 
    </table>
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    </div>

  </body>
</html>