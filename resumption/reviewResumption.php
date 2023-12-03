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
$resumptionID = $_GET['resumptionID'];
$status = $_GET['status'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
$sql1 = "SELECT student.name AS name, student.batchNo AS batchNo, applicantID, applicationDate, yearOfResumption, semOfResumption, yearOfDeferment, semOfDeferment FROM resumption_of_studies_record left join student on resumption_of_studies_record.applicantID=student.studentID WHERE resumptionID = '$resumptionID'";

$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $applicationDate=$row['applicationDate'];
    $applicantID=$row['applicantID'];
    $applicationName=$row['name'];
    $batchNo=$row['batchNo'];
    $yearOfResumption=$row['yearOfResumption'];
    $semOfResumption=$row['semOfResumption'];
    $yearOfDeferment=$row['yearOfDeferment'];
    $semOfDeferment=$row['semOfDeferment'];
  }
}            

//Check is the temporary data exist
$sql = "SELECT * FROM resumption_temporary WHERE id = '$resumptionID'";
$resultTemp = $conn->query($sql);

if ($resultTemp->num_rows > 0) {
    $row = $resultTemp->fetch_assoc();
    $tempDeanOrHeadAcknowledge = $row['deanOrHeadAcknowledge']; 
    $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
    $tempAaroAcknowledge = $row['aaroAcknowledge']; 
    $tempAaroComment = $row['aaroComment']; 
    $tempAfoAcknowledge = $row['afoAcknowledge']; 
    $tempAfoComment = $row['afoComment']; 
}


//Save temporary data
if (isset($_POST['save'])) {
  $resumptionID = $_POST['resumptionID'];
  $tempDecision = $_POST['decision'];
    if ($tempDecision == 'ack') {
        $tempAckResult = 1;
    } elseif ($tempDecision == 'notAck') {
        $tempAckResult = 0;
    }
  $tempComment = $_POST['comment'];

  $sql = "SELECT * FROM resumption_temporary WHERE id = '$resumptionID'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tempDeanOrHeadAcknowledge = $row['deanOrHeadAcknowledge']; 
      $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
      $tempAaroAcknowledge = $row['aaroAcknowledge']; 
      $tempAaroComment = $row['aaroComment']; 
      $tempAfoAcknowledge = $row['afoAcknowledge']; 
      $tempAfoComment = $row['afoComment']; 

      // Check if updating an existing temporary record
      if ($position == 'deanOrHod') {
        $sql = "UPDATE resumption_temporary
                SET deanOrHeadAcknowledge = '$tempAckResult',
                    deanOrHeadComment = '$tempComment'
                WHERE id = '$resumptionID'";
      }elseif ($position == 'aaro') {
        $sql = "UPDATE resumption_temporary
                SET aaroAcknowledge = '$tempAckResult',
                    aaroComment = '$tempComment'
                WHERE id = '$resumptionID'";
      } elseif ($position == 'afo') {
        $sql = "UPDATE resumption_temporary
                SET afoAcknowledge = '$tempAckResult',
                    afoComment = '$tempComment'
                WHERE id = '$resumptionID'";
      } 

    $result = $conn->query($sql);
  } else{

  if($position == 'deanOrHod') {
      $sql = "INSERT INTO resumption_temporary 
              (id, deanOrHeadAcknowledge, deanOrHeadComment)
              VALUES ('$resumptionID', '$tempAckResult', '$tempComment')";
  } elseif($position == 'aaro') {
    $sql = "INSERT INTO resumption_temporary 
            (id, aaroAcknowledge, aaroComment)
            VALUES ('$resumptionID', '$tempAckResult', '$tempComment')";
  }elseif($position == 'afo') {
        $sql = "INSERT INTO resumption_temporary 
                (id, afoAcknowledge, afoComment)
                VALUES ('$resumptionID', '$tempAckResult', '$tempComment')";
  }
    $result = $conn->query($sql);
}

  if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Record saved successfully!");'; 
      echo 'window.location = "viewResumptionApplied.php";';
      echo '</script>';
  } else {
      echo "Error: " . $conn->error;
  }
}

 
  // Submit the review data
  if(isset($_POST['submit'])){
    $resumptionID = $_POST['resumptionID'];

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
      $sql = "UPDATE resumption_of_studies_record
      SET deanOrHeadAcknowledge = '$ackResult',
          deanOrHeadComment = '$comment',
          deanOrHeadSignature = '1',
          deanOrHeadID = '$userid',
          deanOrHeadAcknowledgeDate = '$acknowledgeDate'
      WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    } elseif($position =='aaro'){
      $sql = "UPDATE resumption_of_studies_record
      SET aaroAcknowledge = '$ackResult',
      aaroComment = '$comment',
      aaroSignature = '1',
      aaroID = '$userid',
      aaroAcknowledgeDate = '$acknowledgeDate'
      WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    } elseif($position =='afo'){
      $sql = "UPDATE resumption_of_studies_record
      SET afoAcknowledge = '$ackResult',
      afoComment = '$comment',
      afoSignature = '1',
      afoID = '$userid',
      afoAcknowledgeDate = '$acknowledgeDate'
      WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    }

    if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Successfully submitted!");'; 
      echo 'window.location = "viewResumptionApplied.php";';
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
      location.href = 'viewResumptionApplied.php';
    }
  }
  function back() {
    location.href = 'viewResumptionApplied.php';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Resumption of Studies Application</h3>
  </div>
    <div class="row" style="margin:40px; margin-top:15px">
    <label for="" class="form-label" >Application Details</label>
    <table class="table">  
        <tr>
          <th class="thInfo">Application ID</th><td class="table-light"><?php echo $resumptionID; ?></td>
          <th class="thInfo">Application Date</th><td class="table-light"><?php echo $applicationDate; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Student Name</th><td class="table-light"><?php echo $applicationName; ?></td>
          <th class="thInfo">Student ID</th><td class="table-light"><?php echo $applicantID; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Student Batch No</th><td class="table-light " colspan="3"><?php echo $batchNo; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Year of Deferment</th><td class="table-light"><?php echo $yearOfDeferment; ?></td>
          <th class="thInfo">Sem of Deferment</th><td class="table-light"><?php echo $semOfDeferment; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Year of Resumption</th><td class="table-light"><?php echo $yearOfResumption; ?></td>
          <th class="thInfo">Sem of Resumption</th><td class="table-light"><?php echo $semOfResumption; ?></td>
        </tr>
    </table>
    <?php 
    if($status == 'Approval'){
      if($position == 'deanOrHod'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Faculty (Head of Department / Dean) Use</b></label>';
      } elseif($position =='aaro'){

        //Get review details of dean/hod
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
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
      </table> <?php
        }

        //get review details of afo
        echo '<label for="" class="form-label" >Account & Finance Office</label>';
        $sql3 = "SELECT administrator.name AS name, afoID AS id, afoAcknowledge AS decision, afoComment AS comment, afoAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.afoID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
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

        echo '<label class="form-label" style="margin-top: 30px;margin-left: 20px; font-size: 110%"><b>Academic Affairs & Registration Office Use</b></label>';  
      } elseif($position =='afo'){

        //get review details of dean/hod
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
            }
          }
          ?>

          //print review details of dean/hod
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

        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Account & Finance Office Use</b></label>';
      
      }
        ?>
      <form  action="reviewResumption.php" method="post" enctype="multipart/form-data">
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
            } elseif ($position == 'afo' && $tempAfoAcknowledge == 0 && $tempAfoAcknowledge != null) {
                echo 'checked';
            }}
            ?>>
            <label class="form-check-label" for="inlineRadio2">Not Acknowledge</label>
          </div>
        </div>
        <div class="row" style="margin: 20px; margin-left: 5px;">
          <label for="comment" class="col-md-1 col-form-label">Comments:</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave a comment here" name="comment" id="comment"><?php
            if ($resultTemp->num_rows > 0) {
              if($position == 'deanOrHod' && $tempDeanOrHeadComment != null){
                echo $tempDeanOrHeadComment;} 
              elseif($position == 'aaro' && $tempAaroComment != null){
                echo $tempAaroComment;} 
              elseif($position == 'afo' && $tempAfoComment != null){
                echo $tempAfoComment;} }
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
        <input type="hidden" name="resumptionID" value="<?php echo $resumptionID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px;";>Submit</button>
        <button name="save" type="submit" class="btn btn-info" style="margin-left:20px;" onclick="showSuccessMessage()";>Save</button>
        <button name="cancel" type="button" class="btn btn-secondary" style="margin-left:20px;" onclick="confirmCancel()";>Cancel</button>
      </form>
      <?php
    
    }elseif($status == 'Completed'){ 
      if($position == 'deanOrHod'){ 
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result = $conn->query($sql);
      }elseif($position == 'aaro'){
        echo '<label for="" class="form-label" >Academic Affairs & Registration Office</label>';
        $sql = "SELECT administrator.name AS name, aaroID AS id, aaroAcknowledge AS decision, aaroComment AS comment, aaroAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.aaroID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result = $conn->query($sql);
      }elseif($position == 'afo'){
        echo '<label for="" class="form-label" >Account & Finance Office</label>';
        $sql = "SELECT administrator.name AS name, afoID AS id, afoAcknowledge AS decision, afoComment AS comment, afoAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.afoID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result = $conn->query($sql);
      }    

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $name=$row['name'];
          $id=$row['id'];
          $decision=$row['decision'];
          $comment=$row['comment'];
          $acknowledgeDate=$row['acknowledgeDate'];
          if($decision == 1){
            $acknowledge = 'Acknowledge';
          }elseif($decision == 0){
            $acknowledge = 'Not Acknowledge';
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
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    <?php
      } 
    } elseif($status == 'AllDone'){
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadAcknowledge AS decision, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknoewledgeDate=$row['acknoewledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
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
            <th class="thReview">Date</th><td class="table-light"><?php echo $acknoewledgeDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
      </table>
      <?php
        } 
        
        echo '<label for="" class="form-label" >Academic Affairs & Registration Office</label>';
        $sql2 = "SELECT administrator.name AS name, aaroID AS id, aaroAcknowledge AS decision, aaroComment AS comment, aaroAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.aaroID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
          while ($row = $result2->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
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

        echo '<label for="" class="form-label" >Account & Finance Office</label>';
        $sql3 = "SELECT administrator.name AS name, afoID AS id, afoAcknowledge AS decision, afoComment AS comment, afoAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.afoID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $decision=$row['decision'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
            if($decision == 1){
              $acknowledge = 'Acknowledge';
            }elseif($decision == 0){
              $acknowledge = 'Not Acknowledge';
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
      <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
      <?php
        }       
    }?>
  
    </div>
  </div>
  </body>
</html>