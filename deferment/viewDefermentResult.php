<?php
include '../config.php';

session_start();


if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}


$defermentID = $_GET['defermentID'];
$status = $_GET['status'];

$sql1 = "SELECT category, reasons, student.name as studentName, student.studentID as studentID, student.contactNo, student.batchNo, student.icPassport as icPassport, student.totalCreditsEarned as totalCreditsEarned, student.programme as programme, student.mailingAddress as mailingAddress, applicationDate FROM deferment_record LEFT JOIN student ON deferment_record.applicantID=student.studentID WHERE deferment_record.defermentID = '$defermentID'";

$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $studentName=$row['studentName'];
    $studentID=$row['studentID'];
    $contactNo=$row['contactNo'];
    $batchNo=$row['batchNo'];
    $icPassport=$row['icPassport'];
    $totalCreditsEarned=$row['totalCreditsEarned'];
    $programme=$row['programme'];
    $mailingAddress=$row['mailingAddress'];
    $type=$row['category'];
      if($type == 1){
        $category = 'Deferment';
      }elseif($type == 0){
        $category = 'Withdrawal';
      }
    $reasons=$row['reasons'];
    $applicationDate=$row['applicationDate'];
  }
}   


$sql2 = "SELECT isoSignature, saoSignature, counselingSignature, sroSignature, libSignature, afoSignature, hodSignature, aaroSignature, registrarSignature FROM deferment_record WHERE defermentID = '$defermentID'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $isoSignature=$row['isoSignature'];
            $saoSignature=$row['saoSignature'];
            $counselingSignature=$row['counselingSignature'];
            $sroSignature=$row['sroSignature'];
            $libSignature=$row['libSignature'];
            $afoSignature=$row['afoSignature'];
            $hodSignature=$row['hodSignature'];
            $aaroSignature=$row['aaroSignature'];
            $registrarSignature=$row['registrarSignature'];
        }
      }

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function back() {
    location.href = 'viewDeferment.php';
  }
</script>

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

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Deferment/ Withdrawal Application Result</h3>
  </div>
  <div class="row" style="margin:40px; margin-top:15px">
    <label for="" class="form-label" >Application Details</label>
    <table class="table">  
        <tr>
          <th class="thInfo">Name</th><td class="table-light"><?php echo $studentName; ?></td>
          <th class="thInfo">Contact No.</th><td class="table-light"><?php echo $contactNo; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Student ID</th><td class="table-light"><?php echo $studentID; ?></td>
          <th class="thInfo">Batch No.</th><td class="table-light"><?php echo $batchNo; ?></td>
        </tr>
        <tr>
          <th class="thInfo">NRIC/Passport No.</th><td class="table-light"><?php echo $icPassport; ?></td>
          <th class="thInfo">Total Credits Earned</th><td class="table-light"><?php echo $totalCreditsEarned; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Programme</th><td class="table-light" colspan="3"><?php echo $programme; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Mailing Adress</th><td class="table-light" colspan="3"><?php echo $mailingAddress; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Category</th><td class="table-light" colspan="3"><?php echo $category; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Reason</th><td class="table-light " colspan="3"><?php echo $reasons; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Applcation Date</th><td class="table-light" colspan="3"><?php echo $applicationDate; ?></td>
        </tr>
    </table>

        <?php 

          if($isoSignature == 1){
            echo '<label for="" class="form-label" >International Student Office</label>';
            $sql = "SELECT administrator.name AS name, isoRemarks AS remarks, isoDate AS acknowledgeDate FROM deferment_record left join administrator on deferment_record.isoID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          } 
    
          if($saoSignature == 1){
            echo '<label for="" class="form-label" >Student Affairs Office</label>';
            $sql = "SELECT administrator.name AS name, saoRemarks AS remarks, saoDate AS acknowledgeDate, scholarship FROM deferment_record left join administrator on deferment_record.saoID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                  $type=$row['scholarship'];
                  if($type == 1){
                    $scholarship = 'Yes';
                  }elseif($type == 0){
                    $scholarship = 'No';
                  }
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Scholarship Holder</th><td class="table-light" colspan="3"><?php echo $scholarship; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          } 
    
          if($counselingSignature == 1){
            echo '<label for="" class="form-label" >Student Affairs Office (Counseling Unit)</label>';
            $sql = "SELECT administrator.name AS name, counselingRemarks AS remarks, counselingDate AS acknowledgeDate FROM deferment_record left join administrator on deferment_record.counselingID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          }  
    
          if($sroSignature == 1){
            echo '<label for="" class="form-label" >Student Residence Office</label>';
            $sql = "SELECT administrator.name AS name, sroRemarks AS remarks, sroDate AS acknowledgeDate, returnedDocument FROM deferment_record left join administrator on deferment_record.sroID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                  $type=$row['returnedDocument'];
                  if($type == 1){
                    $returnedDocument = 'Yes';
                  }elseif($type == 0){
                    $returnedDocument = 'No';
                  }
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Returned document</th><td class="table-light" colspan="3"><?php echo $returnedDocument; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          } 
    
          if($libSignature == 1){
            echo '<label for="" class="form-label" >Library</label>';
            $sql = "SELECT administrator.name AS name, libRemarks AS remarks, libDate AS acknowledgeDate, overdueBooks FROM deferment_record left join administrator on deferment_record.libID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                  $type=$row['overdueBooks'];
                  if($type == 1){
                    $overdueBooks = 'Yes';
                  }elseif($type == 0){
                    $overdueBooks = 'No';
                  }
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Overdue books</th><td class="table-light" colspan="3"><?php echo $overdueBooks; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          }
    
          if($afoSignature == 1){
            echo '<label for="" class="form-label" >Account & Finance Office</label>';
            $sql = "SELECT administrator.name AS name, afoRemarks AS remarks, afoDate AS acknowledgeDate, feesOverdue, fees, returnedDeposit FROM deferment_record left join administrator on deferment_record.afoID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                  $type=$row['feesOverdue'];
                  if($type == 1){
                    $feesOverdue = 'Yes';
                  }elseif($type == 0){
                    $feesOverdue = 'No';
                  }
                  $fees=$row['fees'];
                  $returnedDeposit=$row['returnedDeposit'];
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">School fees overdue</th><td class="table-light" colspan="3"><?php echo $feesOverdue; if($type==1){ echo " RM ".$fees;}?></td>
                </tr> 
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Returned Deposit</th><td class="table-light " colspan="3"><?php echo $returnedDeposit; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          } 
    
          if($hodSignature == 1){
            echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
            $sql = "SELECT administrator.name AS name, hodRemarks AS remarks, hodDate AS acknowledgeDate FROM deferment_record left join administrator on deferment_record.afoID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr>  
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
            </table>
            <?php
            } 
    
          } 
    
          if($aaroSignature == 1){
            echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office (Officer)</label>';
            $sql = "SELECT administrator.name AS name, aaroRemarks AS remarks, aaroDate AS acknowledgeDate, registrarDecision FROM deferment_record left join administrator on deferment_record.aaroID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr>  
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr>  
            </table>
            <?php
            } 
    
          } 
    
          if($registrarSignature == 1){
            echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office (Registrar)</label>';
            $sql = "SELECT administrator.name AS name, registrarRemarks AS remarks, registrarDate AS acknowledgeDate, registrarDecision FROM deferment_record left join administrator on deferment_record.registrarID=administrator.administratorID WHERE defermentID = '$defermentID'";
            $result = $conn->query($sql);
    
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $name=$row['name'];
                  $remarks=$row['remarks'];
                  $acknowledgeDate=$row['acknowledgeDate'];
                  $type=$row['registrarDecision'];
                  if($type == 1){
                    $registrarDecision = 'Yes';
                  }elseif($type == 0){
                    $registrarDecision = 'No';
                  }
                }
                ?>
              <table class="table">  
                <tr>
                  <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
                  <th class="thReview">date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
                </tr>  
                <tr>
                  <th class="thReview">Remarks / Suggestions</th><td class="table-light " colspan="3"><?php echo $remarks; ?></td>
                </tr> 
                <tr>
                  <th class="thReview">Decision</th><td class="table-light" colspan="3"><?php echo $registrarDecision; ?></td>
                </tr>
            </table>
            <?php
            } 
    
          } 
        
        
        ?> 
        
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    </div>
  </div>

  </body>
</html>
