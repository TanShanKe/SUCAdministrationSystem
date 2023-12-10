<?php
include '../config.php';
session_start();

$allowedPositions = ["afo", "aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  
  exit();
}

$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM document_record";
$result1 = $conn->query($sql1);
$years = array();
while ($row = $result1->fetch_assoc()) {
    $years[] = $row['year'];
}
sort($years);

$sql2 = "SELECT MAX(YEAR(applicationDate)) AS latestYear, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) IN (10, 11, 12, 1, 2) THEN 3 ELSE 1 END AS latestSem FROM document_record";
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
    <div class="d-flex justify-content-center">
        <h3 style="margin-right: 20px">Document Application</h3>
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
          <?php if($position=='afo') {?>
            <option value="review" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'review') echo 'selected="selected"'; ?>>Review</option>
            <option value="waitingUpdate" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'waitingPayment') echo 'selected="selected"'; ?>>Waiting Update</option>
            <option value="update" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'update') echo 'selected="selected"'; ?>>Update</option>
            <option value="completed" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'completed') echo 'selected="selected"'; ?>>Completed</option>
          <?php }
          elseif($position=='aaro') {?>
            <option value="collection" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'collection') echo 'selected="selected"'; ?>>Collection</option>
            <option value="collecting" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'collecting') echo 'selected="selected"'; ?>>Collecting</option>
            <option value="collected" <?php if (isset($_POST['selected_status']) && $_POST['selected_status'] == 'collected') echo 'selected="selected"'; ?>>Collected</option>
          <?php }
          ?>
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
          <th>Applicant ID</th>
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
          $sql = "SELECT document_record.documentID, applicantID, applicationDate, afoDecision, afoSignature, officialReceipt, updateApplicantSignature, collectionStatus FROM document_record WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 2) {
          $startMonth = 6; // June
          $endMonth = 9;   // September
          $sql = "SELECT document_record.documentID, applicantID, applicationDate, afoDecision, afoSignature, officialReceipt, updateApplicantSignature, collectionStatus FROM document_record WHERE YEAR(applicationDate) = '$selectedYear' AND
          MONTH(applicationDate) BETWEEN $startMonth AND $endMonth";
      } elseif ($selectedSem == 3) {
          $startMonth = 10; // January
          $endMonth = 2;   // February
          $sql = "SELECT document_record.documentID, applicantID, applicationDate, afoDecision, afoSignature, officialReceipt, updateApplicantSignature, collectionStatus FROM document_record WHERE YEAR(applicationDate) = '$selectedYear' AND ((MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth))";
      }

      $keyword="";
      if (isset($_POST['search']) && !empty($_POST['keyword'])) {
        $rowNumber = 1;
        $k=$_POST['keyword'];
        $keyword=" where (documentID like '%".$k."%' OR applicantID like '%".$k."%')";  
        $sql = "SELECT document_record.documentID, applicantID, applicationDate, afoDecision, afoSignature, officialReceipt, updateApplicantSignature, collectionStatus FROM document_record ".$keyword;
      }

      if (isset($_POST['search']) && empty($_POST['keyword'])) {
        echo '<script type="text/javascript">
        alert("Please insert application id to search!");
        </script>';
      }

      if ($position == 'afo') {
        if ($selectedStatus == 'review') {
         $sql .= " AND afoDecision IS NULL ";
        }elseif ($selectedStatus == 'waitingUpdate') {
          $sql .= " AND afoDecision = 0";
        }elseif ($selectedStatus == 'update') {
          $sql .= " AND updateApplicantSignature = 1";
        }elseif($selectedStatus == 'completed'){
          $sql .= " AND afoSignature = 1";
        }
      }

      if ($position == 'aaro') {
        $sql .= " AND afoSignature = 1";
        if ($selectedStatus == 'collection') {
          $sql .= " AND collectionStatus IS NULL ";
         }elseif ($selectedStatus == 'collecting') {
           $sql .= " AND collectionStatus = 0";
         }elseif($selectedStatus == 'collected'){
           $sql .= " AND collectionStatus = 1";
         }
      }

      $sql .= " ORDER BY applicationDate DESC";
      
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $documentID=$row['documentID']; 
            $applicantID=$row['applicantID']; 
            $applicationDate=$row['applicationDate'];
            $afoDecision = $row['afoDecision'];
            $afoSignature = $row['afoSignature'];
            $updateApplicantSignature = $row['updateApplicantSignature'];
            $collectionStatus = $row['collectionStatus'];

            if ($position == 'afo') {
                if (is_null($afoDecision)) {
                    $status = 'review';
                } elseif ($afoDecision == 0) {
                    $status = 'waiting update';
                } if ($updateApplicantSignature == 1 && $afoSignature == 0) {
                  $status = 'update';
                } elseif ($afoSignature == 1) {
                  $status = 'completed';
                }
            } elseif($position == 'aaro'){
              if (is_null($collectionStatus)){
                $status = 'Collection';
              } elseif ($collectionStatus == 0){
                $status = 'Collecting';
              } elseif ($collectionStatus == 1){
                $status = 'Collected';
              } 
            }
                             
            ?>
        <tr>
          <td class="table-light"><?php echo $rowNumber++; ?></td>
          <td class="table-light"><?php echo $documentID; ?></td>
          <td class="table-light"><?php echo $applicantID; ?></td>
          <td class="table-light"><?php echo $applicationDate; ?></td>
          <td class="table-light"><a href="reviewDocument.php?documentID=<?php echo $documentID; ?>&status=<?php echo $status; ?>"><?php echo $status; ?></a>
          </td>
         </tr>   
        <?php 
          }
        }else{
          ?><tr><td class="table-light" colspan="5"><center>No application is found!</center></td></tr><?php
        }
        ?> 
    </table>
    <button name="back" type="button" class="btn btn-secondary" style = "margin:20px;" onclick="back()";>Back</button>
    </div>

  </body>
</html>
