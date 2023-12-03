<?php
include '../config.php';
session_start();


$allowedPositions = ["lecturer", "aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  
  exit();
}


$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM leave_record";
$result1 = $conn->query($sql1);
$years = array();
while ($row = $result1->fetch_assoc()) {
    $years[] = $row['year'];
}
sort($years);

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) IN (10, 11, 12, 1, 2) THEN 3 ELSE 1 END AS latestSem FROM leave_record";
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
    <?php if ($position == 'lecturer') { ?>
      location.href = '../lecturerMain.php';
    <?php } ?>
    <?php if ($position == 'aaro') { ?>
      location.href = '../adminMain.php';
    <?php } ?>
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
    <div class="d-flex justify-content-center">
        <h3 style="margin-right: 20px">Student Incident & Funerary Leave Application</h3>
    </div>
    <form  action="" method="post" enctype="multipart/form-data">
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
          <option value="acknowledge" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'acknowledge') echo 'selected="selected"'; ?>>Acknowledge</option>
          <option value="completed" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'completed') echo 'selected="selected"'; ?>>Completed</option>
        </select>
        <button name="check" type="submit" class="btn btn-secondary" style="margin-left:20px;">Check</button>
        </div>
        <div class="row justify-content-center" style="margin: 20px;">
        <input name="keyword" type="search" style="margin-right: 20px;" placeholder="Search" >
        <button name="search" class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
    </form>
  </div>
</div>

<div class="container-fluid" style="width: 90%;" >
    <div class="row">
    <table class="table "> 
        <tr>
          <th>No</th>
          <th>Application ID</th>
          <th>Student ID</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
        <?php

        $rowNumber = 1;
        $selectedYear = $default_year;
        $selectedSem = $default_sem;
        $selectedStatus = '';

        if (isset($_POST['check'])) {
          $rowNumber = 1;
          $selectedYear = $_POST['selected_year'];
          $selectedSem = $_POST['selected_sem'];
          $selectedStatus = $_POST['selected_status'];
        }

        if ($selectedSem == 1) {
          $startMonth = 3; // March
          $endMonth = 5;   // May
          $sql = " SELECT lecturerSignature, leave_record.applicantID, leave_record.leaveID, leave_record.applicationDate, leave_record.aaroSignature  FROM leave_subject LEFT JOIN leave_record on leave_subject.leaveID=leave_record.leaveID  WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT lecturerSignature, leave_record.applicantID, leave_record.leaveID, leave_record.applicationDate, leave_record.aaroSignature  FROM leave_subject LEFT JOIN leave_record on leave_subject.leaveID=leave_record.leaveID   WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT lecturerSignature, leave_record.applicantID, leave_record.leaveID, leave_record.applicationDate, leave_record.aaroSignature FROM leave_subject LEFT JOIN leave_record on leave_subject.leaveID=leave_record.leaveID WHERE YEAR(applicationDate) = '$selectedYear' AND ((MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth))";
      }

      $keyword="";
      if (isset($_POST['search']) && !empty($_POST['keyword'])) {
        $rowNumber = 1;
        $k=$_POST['keyword'];
        $keyword=" where (leave_subject.leaveID like '%".$k."%' or leave_record.applicantID like '%".$k."%')";  
        $sql = "SELECT lecturerSignature, leave_record.applicantID, leave_record.leaveID, leave_record.applicationDate, leave_record.aaroSignature FROM leave_subject LEFT JOIN leave_record on leave_subject.leaveID=leave_record.leaveID".$keyword;
      }else if (isset($_POST['search']) && empty($_POST['keyword'])) {
        echo '<script type="text/javascript">
        alert("Please insert application id to search!");
        </script>';
      }

      if ($position == 'lecturer') {
        $sql .= " AND leave_subject.lecturerID = '$userid'";
        if ($selectedStatus == 'acknowledge') {
        $sql .= " AND lecturerSignature = 0";
        }elseif($selectedStatus == 'completed'){
        $sql .= " AND lecturerSignature = 1";
        }
        $sql .= " GROUP BY leave_record.leaveID ";
      }

      if ($position == 'aaro') {
        if ($selectedStatus == 'acknowledge') {
          $sql .= " AND aaroSignature = 0 ";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND aaroSignature = 1";
        }
        $sql .= " GROUP BY leave_record.leaveID HAVING
        SUM(CASE WHEN leave_subject.lecturerSignature = 0 OR leave_subject.lecturerSignature IS NULL THEN 1 ELSE 0 END) = 0";
      }

      $sql .= " ORDER BY applicationDate DESC";
      
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $leaveID=$row['leaveID']; 
            $applicationDate=$row['applicationDate'];
            $applicantID=$row['applicantID'];
            $aaroSignature = $row['aaroSignature'];
            $lecturerSignature = $row['lecturerSignature'];

              if($position == 'lecturer'){
                if($lecturerSignature == 0){
                  $status = 'Acknowledge';
                }else{
                  $status = 'Completed';
                }
                
              } elseif($position =='aaro'){
                if($aaroSignature == 0){
                  $status = 'Acknowledge';
                }else{
                  $status = 'Completed';
                }
              }                    
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $leaveID; ?></td>
          <td class="table-light"><?php echo $applicantID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light">
          <a href="reviewLeave.php?leaveID=<?php echo $leaveID; ?>&status=<?php echo $status; ?>"><?php echo $status; ?></a>
          </td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="5"><center>No application is found!</center></td></tr><?php
        }
        ?> 
    </table>
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
      </div>
  </body>
</html>