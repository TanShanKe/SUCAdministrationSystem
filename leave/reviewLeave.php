<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["lecturer", "aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$leaveID = $_GET['leaveID'];
$status = $_GET['status'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//SELECT leave_subject.subjectCode AS subjectCode, subject.name AS subjectName, lecturer.name AS lecturerName, lecturerSignature, leave_record.typeOfLeave, Leave_record.dateOfLeave,leave_record.noOfDays, leave_record.reason, leave_record.applicantID, student.name AS applicantName, student.contactNo, student.batchNo, leave_record.applicationDate, applicantID FROM leave_subject LEFT JOIN leave_record ON leave_subject.leaveID=leave_record.leaveID LEFT JOIN subject ON leave_subject.subjectCode=subject.subjectCode LEFT JOIN lecturer ON leave_subject.lecturerID=lecturer.lecturerID LEFT JOIN student ON leave_record.applicantID=student.studentID WHERE leave_record.leaveID = '$leaveID'

//SELECT typeOfLeave, dateOfLeave, noOfDays, reason, student.name as applicantName, student.studentID as studentID, student.contactNo, student.batchNo, applicationDate FROM leave_record LEFT JOIN student ON leave_record.applicantID=student.studentID WHERE leaveID = '$leaveID'


//Get the applicant info
$sql1 = "SELECT typeOfLeave, dateOfLeave, noOfDays, reason, student.name as studentName, student.studentID as studentID, student.contactNo, student.batchNo, applicationDate, documentalProof FROM leave_record LEFT JOIN student ON leave_record.applicantID=student.studentID WHERE leaveID = '$leaveID'";

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
    $documentalProof=$row['documentalProof'];
  }
}          
  // Submit the review data
  if(isset($_POST['submit'])){
    $leaveID = $_POST['leaveID'];

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $acknowledgeDate=date("Y-m-d"); 

    if($position == 'lecturer'){
      $sql = "UPDATE leave_subject
      SET lecturerSignature = '1',
          lecturerAcknowledgeDate = '$acknowledgeDate'
      WHERE leaveID = '$leaveID' AND lecturerID = '$userid'";
      $result=$conn->query($sql);

    } elseif($position =='aaro'){
      $sql = "UPDATE leave_record
      SET 
      aaroSignature = '1',
      aaroID = '$userid',
      aaroAcknowledgeDate = '$acknowledgeDate'
      WHERE leaveID = '$leaveID'";
      $result=$conn->query($sql);

    }

    if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Successfully submitted!");'; 
      echo 'window.location = "viewLeaveApplied.php";';
      echo '</script>';
    } else {
        echo "Error: " . $conn->error;
    }
  }

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

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

<script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewLeaveApplied.php';
    }
  }
  function back() {
    location.href = 'viewLeaveApplied.php';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Incident & Funerary Leave Application</h3>
  </div>
    <div class="row" style="margin:20px; margin-top:15px">
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
    if($status == 'Acknowledge'){ ?>
      <table class="table "> 
        <tr>
          <th class="thInfo">Subject</th>
          <th class="thInfo">Lecturer Name</th>
          <?php 
            if ($position == 'aaro') { ?>
              <th class="thInfo">Lecturer Acknowledge Date</th> <?php
            }
          ?>
        </tr>

        <?php 
        $sql = "SELECT leave_subject.subjectCode AS subjectCode, subject.name AS subjectName, lecturer.name AS lecturerName, lecturerAcknowledgeDate FROM leave_subject LEFT JOIN leave_record ON leave_subject.leaveID=leave_record.leaveID LEFT JOIN subject ON leave_subject.subjectCode=subject.subjectCode LEFT JOIN lecturer ON leave_subject.lecturerID=lecturer.lecturerID WHERE leave_record.leaveID = '$leaveID'";

        if ($position == 'lecturer') {
          $sql .= " AND leave_subject.lecturerID = '$userid'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $subjectCode=$row['subjectCode'];
            $subjectName=$row['subjectName'];
            $lecturerName=$row['lecturerName'];
            $lecturerAcknowledgeDate=$row['lecturerAcknowledgeDate'];
        ?>
        <tr>
          <td class="table-light"><?php echo $subjectCode .' '. $subjectName; ?></td>
          <td class="table-light"><?php echo $lecturerName; ?></td>
          <?php 
            if ($position == 'aaro') { ?>
              <td class="table-light"><?php echo $lecturerAcknowledgeDate; ?></td> <?php
            }
          ?>
        </tr>   
        <?php 
          }
        }
        ?> 
        </table> <?php
      if($position == 'lecturer'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Lecturer Use</b></label>';
      } elseif($position =='aaro'){
        echo '<label class="form-label" style="margin-top: 30px;margin-left: 20px; font-size: 110%"><b>Academic Affairs & Registration Office Use</b></label>';  
      }
        ?>
      <form  action="reviewLeave.php" method="post" enctype="multipart/form-data">
        <div class="row" style="margin: 20px;">
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        </div>
        <input type="hidden" name="leaveID" value="<?php echo $leaveID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px; float:right;">Submit</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
      </form>
      <?php
      
      } elseif($status == 'Completed'){ 

        ?>
      <table class="table "> 
        <tr>
          <th class="thReview">Subject</th>
          <th class="thReview">Lecturer Name</th>
          <th class="thReview">Lecturer Acknowledge Date</th>
        </tr>

        <?php 
        $sql2 = "SELECT aaroSignature FROM leave_record WHERE leaveID = '$leaveID'";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
          while ($row = $result2->fetch_assoc()) {
            $aaroSignature=$row['aaroSignature'];
          }
        }
        ?>

        <?php 
        $sql = "SELECT leave_subject.subjectCode AS subjectCode, subject.name AS subjectName, lecturer.name AS lecturerName, lecturerAcknowledgeDate, aaroAcknowledgeDate, administrator.name as aaroName, leave_record.aaroSignature FROM leave_subject LEFT JOIN leave_record ON leave_subject.leaveID=leave_record.leaveID LEFT JOIN subject ON leave_subject.subjectCode=subject.subjectCode LEFT JOIN lecturer ON leave_subject.lecturerID=lecturer.lecturerID LEFT JOIN administrator ON leave_record.aaroID=administrator.administratorID WHERE leave_record.leaveID = '$leaveID'";

        if ($position == 'lecturer' && $aaroSignature !=1) {
          $sql .= " AND leave_subject.lecturerID = '$userid'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $subjectCode=$row['subjectCode'];
            $subjectName=$row['subjectName'];
            $lecturerName=$row['lecturerName'];
            $lecturerAcknowledgeDate=$row['lecturerAcknowledgeDate'];
            $aaroAcknowledgeDate=$row['aaroAcknowledgeDate'];
            $aaroName=$row['aaroName'];
        ?>
        <tr>
          <td class="table-light"><?php echo $subjectCode .' '. $subjectName; ?></td>
          <td class="table-light"><?php echo $lecturerName; ?></td>
          <td class="table-light"><?php echo $lecturerAcknowledgeDate; ?></td> 
        </tr>   
        <?php 
          }
        }
        ?> 
        </table> 

        <?php 
        if ($aaroSignature == 1) { ?>
        <label for="" class="form-label" >Academic Affairs, Admission & Registration Office </label>
        <table class="table "> 
        <tr>
          <th class="thReview">AARO Name</th>
          <th class="thReview">AARO Acknowledge Date</th>
        </tr>
        <tr>
          <td class="table-light"><?php echo $aaroName; ?></td>
          <td class="table-light"><?php echo $aaroAcknowledgeDate; ?></td>
        </tr>
        <?php
            }
          ?>
        </table>
    </div>
    <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-right:20px; float:right;" onclick="back()";>Back</button>
        <?php   
    }?>
  </div>
  </body>
</html>