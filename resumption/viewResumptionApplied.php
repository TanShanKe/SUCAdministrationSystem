<?php
include '../config.php';

$sql1 = "SELECT DISTINCT YEAR(applicationDate) AS year FROM resumption_of_studies_record";
$result1 = $conn->query($sql1);
$years = array();
while ($row = $result1->fetch_assoc()) {
    $years[] = $row['year'];
}
sort($years);

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
            <option value="">year</option>
          <?php foreach ($years as $year) : ?>
            <option value="<?php echo $year; ?>">
              <?php echo $year; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label for="sem" class="form-label" style="margin-top: 5px; margin-right: 30px;">Select Sem:</label>
        <select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
            <option value="0">sem</option>
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
          <th>Student ID</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
        <?php
        if (isset($_POST['selected_year']) && isset($_POST['selected_sem'])) {
          $rowNumber = 1;
          $selectedYear = $_POST['selected_year'];
          $selectedSem = $_POST['selected_sem'];
        
          // Define semester date ranges
          if ($selectedSem == 1) {
            $startMonth = 3; // March
            $endMonth = 5;   // May
            $sql = "SELECT resumptionID, applicantID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature FROM resumption_of_studies_record
            WHERE YEAR(applicationDate) = '$selectedYear' AND
            MONTH(applicationDate) BETWEEN $startMonth AND $endMonth ORDER BY applicationDate DESC";
        } elseif ($selectedSem == 2) {
            $startMonth = 6; // June
            $endMonth = 9;   // September
            $sql = "SELECT resumptionID, applicantID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature  FROM resumption_of_studies_record
            WHERE YEAR(applicationDate) = '$selectedYear' AND
            MONTH(applicationDate) BETWEEN $startMonth AND $endMonth ORDER BY applicationDate DESC";
        } elseif ($selectedSem == 3) {
            $startMonth = 10; // January
            $endMonth = 2;   // February
            $sql = "SELECT resumptionID, applicantID, applicationDate, aaroAcknowledge, aaroSignature, afoAcknowledge, afoSignature, deanOrHeadAcknowledge, deanOrHeadSignature  FROM resumption_of_studies_record
                  WHERE YEAR(applicationDate) = '$selectedYear' AND (
                    (MONTH(applicationDate) >= $startMonth AND MONTH(applicationDate) <= 12) OR
                    (MONTH(applicationDate) >= 1 AND MONTH(applicationDate) <= $endMonth)
                  ) ORDER BY applicationDate DESC";
        }
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $resumptionID=$row['resumptionID']; 
              $applicantID=$row['applicantID']; 
              $applicationDate=$row['applicationDate'];
              $aaroAcknowledge = $row['aaroAcknowledge'];
              $aaroSignature = $row['aaroSignature'];
              $afoAcknowledge = $row['afoAcknowledge'];
              $afoSignature = $row['afoSignature'];
              $deanOrHeadAcknowledge = $row['deanOrHeadAcknowledge'];
              $deanOrHeadSignature = $row['deanOrHeadSignature'];
                        
                if ($aaroSignature == 0 || $afoSignature == 0 || $deanOrHeadSignature == 0) {
                    $status = 'Approval';
                } else{
                    $status = 'Completed';
                }
              ?>
          <tr>
            <td class="table-light"><?php echo $rowNumber++; ?></td>
            <td class="table-light"><?php echo $resumptionID; ?></td>
            <td class="table-light"><?php echo $applicantID; ?></td>
            <td class="table-light"><?php echo $applicationDate; ?></td>
            <td class="table-light"><?php if ($status === 'Review' || $status === 'Disapproved'): ?>
            <a href="reviewResumption.php?resumptionID=<?php echo $resumptionID; ?>"><?php echo $status; ?></a>
            <?php else: ?><?php echo $status; ?><?php endif; ?></td>
           </tr>   
          <?php 
            }
          } else{
            echo 'no application is found';
          }
        }
        ?> 
    </table>
    </div>
  </div>

  </body>
</html>
