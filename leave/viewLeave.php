<?php
include '../config.php';
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM leave_record WHERE applicantID = '$userid'";
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

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) BETWEEN 10 AND 12 THEN 3 WHEN MONTH(MAX(applicationDate)) BETWEEN 1 AND 2 THEN 4 ELSE 1 END AS latestSem FROM leave_record WHERE applicantID = '$userid'";
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

.pagination a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: dodgerblue;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

</style>

  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <div class="d-flex justify-content-center">
      <h3 style="margin-right: 20px">Incident & Funerary Leave Application</h3>
      <button class="btn btn-primary" type="button" onclick="location.href='applyLeave.php';">Apply</button>
      </div>
      <div class="row justify-content-center" style="margin: 20px;">
        <label for="year" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Year:</label>
        <select name="selected_year" id="selected_year" style="margin-right: 30px;">
        <?php foreach ($years as $year) : ?>
          <option value="<?php echo $year; ?>" <?php if (
          (isset($_POST['selected_year']) && $_POST['selected_year'] == $year) || 
          (!isset($_POST['selected_year']) && $year == $default_year) || 
          (!empty($selectedYear) && $selectedYear == $year) 
          ) echo 'selected="selected"'; ?>>
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

<div class="container-fluid" style="width: 90%;" >
    <div class="row">
    <table class="table "> 
        <tr>
          <th>No</th>
          <th>Application ID</th>
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
          $sql = "SELECT leaveID, applicationDate, aaroSignature FROM leave_record WHERE YEAR(applicationDate) = '$selectedYear' AND MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid' ORDER BY leaveID DESC";

      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT leaveID, applicationDate, aaroSignature FROM leave_record WHERE YEAR(applicationDate) = '$selectedYear' AND MONTH(applicationDate) BETWEEN $startMonth AND $endMonth AND
          applicantID = '$userid' ORDER BY leaveID DESC";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT leaveID, applicationDate, aaroSignature FROM leave_record 
          WHERE 
          (( YEAR(applicationDate) = '$selectedYear' AND 
            (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) 
          ) 
          OR 
            ( YEAR(applicationDate) = '$selectedYear'+1 AND 
            (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth) 
          ))
          AND applicantID = '$userid' ORDER BY leaveID DESC";
      }

        $result = $conn->query($sql);

        //pagination
        $results_per_page = 10; 
        $number_of_result = mysqli_num_rows($result); 
        $number_of_page = ceil ($number_of_result / $results_per_page);  

        $page = (!isset ($_GET['page'])) ? 1 : $_GET['page'];

        $page_first_result = ($page-1) * $results_per_page; 

        $sql2 = $sql." LIMIT ".$page_first_result . ',' . $results_per_page; 
        $rowNumber = $page_first_result + 1;
      
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
          while ($row = $result2->fetch_assoc()) {
            $leaveID=$row['leaveID']; 
            $applicationDate=$row['applicationDate'];
            $aaroSignature = $row['aaroSignature'];
                      
              if ($aaroSignature == 0) {
                  $status = 'Pending';
              }
               elseif ($aaroSignature == 1) {
                  $status = 'Done';
              }
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $leaveID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light">
          <a href="viewLeaveResult.php?leaveID=<?php echo $leaveID; ?>"><?php echo $status; ?></a>
          </td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="4"><center>No application is done in this semester!</center></td></tr><?php
        }
        ?> 
    </table>
    </div>
    <div class="pagination">
    <?php 
    //pagination
    $previouspage = $page-1;
    if($previouspage < 1){
      $previouspage = 1;
    }
    if($number_of_page>1){
      echo '<a href="viewLeave.php?page=' . $previouspage . '&year='.$selectedYear.'&sem='.$selectedSem.'">&laquo;</a>';
      for($i = 1; $i<= $number_of_page; $i++) {  
        $class = ($page == $i) ? 'active' : '';
        echo '<a href = "viewLeave.php?page=' . $i . '&year='.$selectedYear.'&sem='.$selectedSem.'" class="' . $class . '">' . $i . ' </a>';  
      }  
      $nextpage = $page+1;
      if($nextpage > $number_of_page){
        $nextpage = $page;
      }
      echo '<a href="viewLeave.php?page=' . $nextpage . '&year='.$selectedYear.'&sem='.$selectedSem.'">&raquo;</a>';
    } ?>
    </div>
    <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; float: right;" onclick="back()";>Back</button>
  </div>
  </body>
</html>
