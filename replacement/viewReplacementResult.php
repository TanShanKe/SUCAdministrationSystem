<?php
include '../config.php';

session_start();

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'lecturer') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$changeClassID = $_GET['changeClassID'];
$status = $_GET['status'];

$sql = "SELECT change_class_record.subjectCode AS subjectCode, typeOfChange, existingDate, existingDay, date_format(existingTime,'%H:%i') AS existingTime, hour, existingVenue, newDate, newDay, date_format(newTime,'%H:%i') AS newTime, newVenue, reason, subject.name AS subjectName, lecturer.name AS lecturerName, applicant.name AS applicantName, applicationDate, aaroAcknowledge, aaroComment, aaroAcknowledgeDate, deanOrHeadAcknowledge, deanOrHeadComment, deanOrHeadAcknowledgeDate FROM change_class_record LEFT JOIN subject ON change_class_record.subjectCode = subject.subjectCode LEFT JOIN lecturer ON change_class_record.lecturerID = lecturer.lecturerID LEFT JOIN lecturer AS applicant ON change_class_record.applicantID = applicant.lecturerID WHERE changeClassID = '$changeClassID'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

    $subjectCode=$row['subjectCode'];
    $type=$row['typeOfChange'];
      if($type == 1){
        $typeOfChange = 'Class Replacement';
      }elseif($type == 0){
        $typeOfChange = 'Permanent';
      }
    $existingDate=$row['existingDate'];
    $existingDay=$row['existingDay'];
    $existingTime=$row['existingTime'];
    $hour=$row['hour'];
    $existingVenue=$row['existingVenue'];
    $newDate=$row['newDate'];
    $newDay=$row['newDay'];
    $newTime=$row['newTime'];
    $newVenue=$row['newVenue'];
    $reason=$row['reason'];
    $subjectName=$row['subjectName'];
    $lecturerName=$row['lecturerName'];
    $applicantName=$row['applicantName'];
    $applicationDate=$row['applicationDate'];
    $aaroAcknowledge = $row['aaroAcknowledge'];
    $aaroComment = $row['aaroComment'];
    $deanOrHeadAcknowledge = $row['deanOrHeadAcknowledge'];
    $deanOrHeadComment = $row['deanOrHeadComment'];

    $time1 = DateTime::createFromFormat('H:i', $existingTime);
    $time1->modify("+" . $hour . " hours");

    $time2 = DateTime::createFromFormat('H:i', $newTime);
    $time2->modify("+" . $hour . " hours");

    $existingEndTime= $time1->format('H:i');
    $newEndTime= $time2->format('H:i');

    if ($deanOrHeadAcknowledge == 0) {
      $deanOrHeadAcknowledge = 'Disapproved';
    } if ($deanOrHeadAcknowledge == 1) {
      $deanOrHeadAcknowledge = 'Approved';
    } 
    if ($aaroAcknowledge == 0) {
      $aaroAcknowledge = 'Rejected';
    }elseif ($aaroAcknowledge == 1) {
      $aaroAcknowledge = 'Acknowledged';
    }
  }
}            

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function back() {
    location.href = 'viewReplacement.php';
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
  <h3 style="margin-right: 20px">Replacement/ Permanent Change of Class Room Venue/ Time Application Result</h3>
  </div>
  <div class="row" style="margin:20px; margin-top:15px">
    <label for="">Application Details</label>
    <table class="table">  
        <tr>
          <th class="thInfo">Type Of Change</th><td class="table-light" colspan="2"><?php echo $typeOfChange; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Subject Code & Name</th><td class="table-light" colspan="2"><?php echo $subjectCode." ".$subjectName; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Lecturer Name</th><td class="table-light " colspan="2"><?php echo $lecturerName; ?></td>
        </tr>
        <tr>
          <th class="thInfo"></th>
          <th class="thInfo"><center>Existing</center></th>
          <th class="thInfo"><center>New</center></th>
        </tr> 
        <?php 
        if($type == 1){ ?>
          <tr>
          <th class="thInfo">Date</th>
          <td class="table-light"><?php echo $existingDate; ?></td>
          <td class="table-light"><?php echo $newDate; ?></td>
        </tr> 
        <?php }elseif($type == 0){ ?>
          <tr>
          <th class="thInfo">Day</th>
          <td class="table-light"><?php echo $existingDay;?></td>
          <td class="table-light"><?php echo $newDay; ?></td>
          </tr>
        <?php  } ?>
        <tr>
          <th class="thInfo">Time</th>
          <td class="table-light"><?php echo $existingTime .' to '. $existingEndTime; ?></td>
          <td class="table-light"><?php echo $newTime .' to '. $newEndTime; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Venue</th>
          <td class="table-light"><?php echo $existingVenue; ?></td>
          <td class="table-light"><?php echo $newVenue; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Reason(s)</th><td class="table-light" colspan="2"><?php echo $reason; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Applicant Name</th><td class="table-light" colspan="2"><?php echo $applicantName; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Application Date</th><td class="table-light" colspan="2"><?php echo $applicationDate; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Documental Proof</th><td class="table-light" colspan="3">
        <?php
        $sql = "SELECT changeClassID, fileName FROM change_class_documentalproof WHERE changeClassID = '$changeClassID'";
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
    <?php if($status=='Approved' || $status == 'Disapproved'){ ?>
      <table class="table">  
      <label for="" >Faculty (Head of Department / Dean)</label>
          <tr>
            <th class="thReview">Acknowledge / <br>Not Acknowledge</th><td class="table-light"><?php echo $deanOrHeadAcknowledge; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment / Remarks</th><td class="table-light" ><?php echo $deanOrHeadComment; ?></td>
          </tr> 
      </table>
      <table class="table"> 
      <label for="">Academic Affairs, Admission & Registration Office </label>
          <tr>
            <th class="thReview">Acknowledge / <br>Not Acknowledge</th><td class="table-light"><?php echo $aaroAcknowledge; ?></td>
          </tr>
          <tr>
            <th class="thReview">Comment / Remarks</th><td class="table-light"><?php echo $aaroComment; ?></td>
          </tr>   
      </table>
    <?php } ?>
    </div>
    <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-right:20px; float: right;" onclick="back()";>Back</button>
  </div>
  </body>
</html>
