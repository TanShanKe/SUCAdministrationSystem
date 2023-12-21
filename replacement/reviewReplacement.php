<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["deanOrHod", "aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$changeClassID = $_GET['changeClassID'];
$status = $_GET['status'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
$sql1 = "SELECT change_class_record.subjectCode AS subjectCode, typeOfChange, existingDate, existingDay, date_format(existingTime,'%H:%i') AS existingTime, hour, existingVenue, newDate, newDay, date_format(newTime,'%H:%i') AS newTime, newVenue, reason, subject.name AS subjectName, lecturer.name AS lecturerName, applicant.name AS applicantName, applicationDate FROM change_class_record LEFT JOIN subject ON change_class_record.subjectCode = subject.subjectCode LEFT JOIN lecturer ON change_class_record.lecturerID = lecturer.lecturerID LEFT JOIN lecturer AS applicant ON change_class_record.applicantID = applicant.lecturerID WHERE changeClassID = '$changeClassID'";

$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
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

    $time1 = DateTime::createFromFormat('H:i', $existingTime);
    $time1->modify("+" . $hour . " hours");

    $time2 = DateTime::createFromFormat('H:i', $newTime);
    $time2->modify("+" . $hour . " hours");

    $existingEndTime= $time1->format('H:i');
    $newEndTime= $time2->format('H:i');
  }
}            

//Check is the temporary data exist
$sql = "SELECT * FROM change_class_temporary WHERE id = '$changeClassID'";
$resultTemp = $conn->query($sql);

if ($resultTemp->num_rows > 0) {
    $row = $resultTemp->fetch_assoc();
    $tempDeanOrHeadAcknowledge = $row['deanOrHeadAcknowledge']; 
    $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
    $tempAaroAcknowledge = $row['aaroAcknowledge']; 
    $tempAaroComment = $row['aaroComment']; 
}


//Save temporary data
if (isset($_POST['save'])) {
  $changeClassID = $_POST['changeClassID'];
  $tempDecision = $_POST['decision'];
    if ($tempDecision == 'ack') {
        $tempAckResult = 1;
    } elseif ($tempDecision == 'notAck') {
        $tempAckResult = 0;
    }
  $tempComment = $_POST['comment'];

  $sql = "SELECT * FROM change_class_temporary WHERE id = '$changeClassID'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tempDeanOrHeadAcknowledge = $row['deanOrHeadAcknowledge']; 
      $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
      $tempAaroAcknowledge = $row['aaroAcknowledge']; 
      $tempAaroComment = $row['aaroComment']; 

      // Check if updating an existing temporary record
      if ($position == 'deanOrHod') {
        $sql = "UPDATE change_class_temporary
                SET deanOrHeadAcknowledge = '$tempAckResult',
                    deanOrHeadComment = '$tempComment'
                WHERE id = '$changeClassID'";
      }elseif ($position == 'aaro') {
        $sql = "UPDATE change_class_temporary
                SET aaroAcknowledge = '$tempAckResult',
                    aaroComment = '$tempComment'
                WHERE id = '$changeClassID'";
      }

    $result = $conn->query($sql);
  } else{
    

  if($position == 'deanOrHod') {
      $sql = "INSERT INTO change_class_temporary 
              (id, deanOrHeadAcknowledge, deanOrHeadComment)
              VALUES ('$changeClassID', '$tempAckResult', '$tempComment')";
  } elseif($position == 'aaro') {
    $sql = "INSERT INTO change_class_temporary 
            (id, aaroAcknowledge, aaroComment)
            VALUES ('$changeClassID', '$tempAckResult', '$tempComment')";
  }
    $result = $conn->query($sql);
}

  if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Record saved successfully!");'; 
      echo 'window.location = "viewReplacementApplied.php";';
      echo '</script>';
  } else {
      echo "Error: " . $conn->error;
  }
}

 
  // Submit the review data
  if(isset($_POST['submit'])){
    $changeClassID = $_POST['changeClassID'];

    $decision = $_POST['decision'];
    if ($decision == 'ack') {
        $ackResult = 1;
    } elseif ($decision == 'notAck') {
        $ackResult = 0;
    }

    $comment = $_POST['comment'];

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $acknowledgeDate=date("Y-m-d"); 

    if($position == 'deanOrHod'){
      $sql = "UPDATE change_class_record
      SET deanOrHeadAcknowledge = '$ackResult',
          deanOrHeadComment = '$comment',
          deanOrHeadSignature = '1',
          deanOrHeadID = '$userid',
          deanOrHeadAcknowledgeDate = '$acknowledgeDate'
      WHERE changeClassID = '$changeClassID'";
      $result=$conn->query($sql);
    } elseif($position =='aaro'){
      $sql = "UPDATE change_class_record
      SET aaroAcknowledge = '$ackResult',
      aaroComment = '$comment',
      aaroSignature = '1',
      aaroID = '$userid',
      aaroAcknowledgeDate = '$acknowledgeDate'
      WHERE changeClassID = '$changeClassID'";
      $result=$conn->query($sql);

      $sql2="DELETE FROM change_class_temporary WHERE ID='$changeClassID'";
      $result2=$conn->query($sql2);
    }

    if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Successfully submitted!");'; 
      echo 'window.location = "viewReplacementApplied.php";';
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
      location.href = 'viewReplacementApplied.php';
    }
  }
  function back() {
    location.href = 'viewReplacementApplied.php';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Replacement/ Permanent Change of Class Room Venue/ Time Application</h3>
  </div>
    <div class="row" style="margin:20px; margin-top:15px">
    <label for="" class="form-label" >Application Details</label>
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
    <?php 
    if($status == 'Review'){
      if($position == 'deanOrHod'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Faculty (Head of Department / Dean) Use</b></label>';
      } elseif($position =='aaro'){
        //Get review details of dean/hod
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM change_class_record left join lecturer on change_class_record.deanOrHeadID=lecturer.lecturerID WHERE changeClassID = '$changeClassID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Approved';
            }elseif($decision == 0){
              $acknowledge = 'Disapproved';
            }
          }
          ?>

          //show review details of dean/hod
        <table class="table">  
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Decision</th><td class="table-light"><?php echo $acknowledge; ?></td>
            <th class="thReview">Date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
      </table> <?php
        }
        echo '<label class="form-label" style="margin-top: 30px;margin-left: 20px; font-size: 110%"><b>Academic Affairs & Registration Office Use</b></label>';  
        }

        ?>
      <form  action="reviewReplacement.php" method="post" enctype="multipart/form-data">
        <div class="row" style="margin: 20px; margin-top:10px">
          <label for="id" class="form-label" style="margin-top: 3px;">Desicion:</label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="decision" id="ack" value="ack"checked>
            <label class="form-check-label" for="inlineRadio1">Acknowledge</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="decision" id="notAck" value="notAck"
            <?php
            if ($resultTemp->num_rows > 0) {
            if ($position == 'deanOrHod' && $tempDeanOrHeadAcknowledge == 0 && $tempDeanOrHeadAcknowledge != null) {
                echo 'checked';
            } elseif ($position == 'aaro' && $tempAaroAcknowledge == 0 && $tempAaroAcknowledge != null) {
                echo 'checked';
            }}
            ?>>
            <label class="form-check-label" for="inlineRadio2">Not Acknowledge</label>
          </div>
        </div>
        <div class="row" style="margin: 20px; margin-left: 5px;">
          <label for="comment" class="col-md-1 col-form-label">Comments:</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave a comment here" name="comment" id="comment" required><?php
            if ($resultTemp->num_rows > 0) {
              if($position == 'deanOrHod' && $tempDeanOrHeadComment != null){
                echo $tempDeanOrHeadComment;} 
              elseif($position == 'aaro' && $tempAaroComment != null){
                echo $tempAaroComment;} }
              ?></textarea>
          </div>
        </div>
        <div class="row" style="margin: 20px;">
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        </div>
        <input type="hidden" name="changeClassID" value="<?php echo $changeClassID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px; float:right;";>Submit</button>
        <button name="save" type="submit" class="btn btn-outline-info" style="margin-left:20px; float:right;" onclick="showSuccessMessage()";>Save</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
      </form>
      <?php
      
      } elseif($status == 'Completed'){ 

        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate, aaroSignature FROM change_class_record left join lecturer on change_class_record.deanOrHeadID=lecturer.lecturerID WHERE changeClassID = '$changeClassID'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Approved';
            }elseif($decision == 0){
              $acknowledge = 'Disapproved';
            }
          }

        ?>

        <table class="table">  
        <tr>
          <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
          <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
        </tr> 
        <tr>
          <th class="thReview">Decision</th><td class="table-light"><?php echo $acknowledge; ?></td>
          <th class="thReview">Date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
        </tr> 
        <tr>
          <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
        </tr> 
    </table>
        
    <?php
        }
        $sql = "SELECT administrator.name AS name, aaroID AS id, aaroAcknowledge AS decision, aaroComment AS comment, aaroAcknowledgeDate AS acknowledgeDate FROM change_class_record LEFT JOIN administrator ON change_class_record.aaroID=administrator.administratorID WHERE changeClassID = '$changeClassID' AND aaroSignature = '1'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledged';
            }elseif($decision == 0){
              $acknowledge = 'Rejected';
            }
          }
        ?>

        
        <label for="" class="form-label" >Academic Affairs, Admission & Registration Office</label>
        <table class="table">  
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Decision</th><td class="table-light"><?php echo $acknowledge; ?></td>
            <th class="thReview">Date</th><td class="table-light"><?php echo $acknowledgeDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
      </table>
    <?php
        }?>

      <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-top:20px; float:right;" onclick="back()";>Back</button>
      <?php
        

  }?>
  
    </div>
  </div>
  </body>
</html>