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

        if($status != 'Pending'){
            $sql = "SELECT leave_subject.subjectCode AS subjectCode, subject.name AS subjectName, lecturer.name AS lecturerName, lecturerAcknowledgeDate, lecturerSignature, aaroAcknowledgeDate, aaroSignature, administrator.name as aaroName FROM leave_subject LEFT JOIN leave_record ON leave_subject.leaveID=leave_record.leaveID LEFT JOIN subject ON leave_subject.subjectCode=subject.subjectCode LEFT JOIN lecturer ON leave_subject.lecturerID=lecturer.lecturerID LEFT JOIN administrator ON leave_record.aaroID=administrator.administratorID WHERE leave_record.leaveID = '$leaveID'";

            $result = $conn->query($sql);
            $count = 0;
            $t=$result->num_rows;
            $i=0;
    
            if ($result->num_rows > 0) { 
              while ($row = $result->fetch_assoc()) {
                $subjectCode=$row['subjectCode'];
                $subjectName=$row['subjectName'];
                $lecturerName=$row['lecturerName'];
                $lecturerAcknowledgeDate=$row['lecturerAcknowledgeDate'];
                $lecturerSignature=$row['lecturerSignature'];
                $aaroAcknowledgeDate=$row['aaroAcknowledgeDate'];
                $aaroName=$row['aaroName'];
                $aaroSignature=$row['aaroSignature'];
                $i++;
    
                if($count == 0){
                  ?>
                  <table class="table "> 
                  <tr>
                    <th class="thReview">Subject</th>
                    <th class="thReview">Lecturer Name</th>
                    <th class="thReview">Lecturer Acknowledge Date</th>
                  </tr> 
                  <?php
                  $count++;
                }?>
                  <tr>
                    <td class="table-light"><?php echo $subjectCode .' '. $subjectName; ?></td>
                    <td class="table-light"><?php echo $lecturerName; ?></td>
                    <td class="table-light">
                    <?php
                    if($lecturerSignature==1){
                      echo $lecturerAcknowledgeDate; 
                    }else{
                      echo 'Pending';
                    }
                     ?></td> 
                  </tr>  
                  <?php 
                }
                if($i==$t){ ?>
                  </table> <?php
                }
              
              if($aaroSignature == 1){
                ?>
                  </table> 
                  <label for="" class="form-label" >Academic Affairs & Registration Office</label>
                  <table class="table "> 
                  <tr>
                    <th class="thReview">AARO Name</th>
                    <th class="thReview">AARO Acknowledge Date</th>
                  </tr>
                  <tr>
                    <td class="table-light"><?php echo $aaroName; ?></td>
                    <td class="table-light"><?php echo $aaroAcknowledgeDate; ?></td>
                  </tr>
                  </table>  
                <?php 
              }
            }
        }
        
        ?> 
        
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    </div>
  </div>

  </body>
</html>
