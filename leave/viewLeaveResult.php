<?php
include '../config.php';

session_start();


if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}


$leaveID = $_GET['leaveID'];

$sql1 = "SELECT typeOfLeave, dateOfLeave, noOfDays, reason, student.name as studentName, student.studentID as studentID, student.contactNo, student.batchNo, applicationDate FROM leave_record LEFT JOIN student ON leave_record.applicantID=student.studentID WHERE leave_record.leaveID = '$leaveID'";

$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $studentName=$row['studentName'];
    $studentID=$row['studentID'];
    $contactNo=$row['contactNo'];
    $batchNo=$row['batchNo'];
    $type=$row['typeOfLeave'];
      if($type == 1){
        $typeOfLeave = 'Incident Leave';
      }elseif($type == 0){
        $typeOfLeave = 'Funerary Leave';
      }
    $dateOfLeave=$row['dateOfLeave'];
    $noOfDays=$row['noOfDays'];
    $reason=$row['reason'];
    $applicationDate=$row['applicationDate'];
  }
}   

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function back() {
    location.href = 'viewLeave.php';
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
  <h3 style="margin-right: 20px">Student Incident & Funerary Leave Application Result</h3>
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
          <th class="thInfo">Type Of Leave</th><td class="table-light" colspan="3"><?php echo $typeOfLeave; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Reason</th><td class="table-light " colspan="3"><?php echo $reason; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Date of Leave</th><td class="table-light"><?php echo $dateOfLeave; ?></td>
          <th class="thInfo">No. of Days</th><td class="table-light"><?php echo $noOfDays; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Applcation Date</th><td class="table-light" colspan="3"><?php echo $applicationDate; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Documental Proof</th><td class="table-light" colspan="3">
        <?php
        $sql = "SELECT leaveID, fileName FROM leave_documentalproof WHERE leaveID = '$leaveID'";
        $result = $conn->query($sql);
        $no =1;
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $fileName=$row['fileName']; ?>
            <a href="<?php echo $fileName; ?>" target="_blank"><?php echo $no."."; ?>Click here to view the documental proof</a> <br>
           <?php
            $no++;
          }
        }
        ?>
        </td>
        </tr>
    </table>

        <?php 
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
        ?> 
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    </div>
  </div>

  </body>
</html>
