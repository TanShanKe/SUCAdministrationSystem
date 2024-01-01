<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["deanOrHod", "aaro", "afo", "registrar"];
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

//Get review info
$sql2 = "SELECT deanOrHeadSignature, aaroSignature, afoSignature, registrarSignature FROM resumption_of_studies_record WHERE resumptionID = '$resumptionID'";

$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $deanOrHeadSignature=$row['deanOrHeadSignature'];
        $afoSignature=$row['afoSignature'];
        $aaroSignature=$row['aaroSignature'];
        $registrarSignature=$row['registrarSignature'];
  }
}

//Check is the temporary data exist
$sql = "SELECT * FROM resumption_temporary WHERE id = '$resumptionID'";
$resultTemp = $conn->query($sql);

if ($resultTemp->num_rows > 0) {
    $row = $resultTemp->fetch_assoc();
    $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
    $tempAaroComment = $row['aaroComment']; 
    $tempAfoComment = $row['afoComment']; 
    $tempAfoFees = $row['afoFees']; 
    $tempRegistrarComment = $row['registrarComment']; 
    $tempRegistrarAcknowledge = $row['registrarAcknowledge']; 

}


//Save temporary data
if (isset($_POST['save'])) {
  $resumptionID = $_POST['resumptionID'];
  if($position == 'registrar'){
    $tempDecision = $_POST['decision'];
    if ($tempDecision == 'ack') {
        $tempAckResult = 1;
    } elseif ($tempDecision == 'notAck') {
        $tempAckResult = 0;
    }
  }
  $tempComment = $_POST['comment'];
  if($position == 'afo'){
    $tempFees = $_POST['fees'];
  }

  $sql = "SELECT * FROM resumption_temporary WHERE id = '$resumptionID'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tempDeanOrHeadComment = $row['deanOrHeadComment']; 
      $tempAaroComment = $row['aaroComment']; 
      $tempAfoComment = $row['afoComment']; 
      $tempAfoFees = $row['afoFees']; 
      $tempRegistrarComment = $row['registrarComment']; 
      $tempRegistrarAcknowledge = $row['registrarAcknowledge']; 

      // Check if updating an existing temporary record
      if ($position == 'deanOrHod') {
        $sql = "UPDATE resumption_temporary
                SET deanOrHeadAcknowledge = '$tempAckResult',
                    deanOrHeadComment = '$tempComment'
                WHERE id = '$resumptionID'";
      } elseif ($position == 'afo') {
        $sql = "UPDATE resumption_temporary
                SET afoFees = '$tempFees',
                    afoComment = '$tempComment'
                WHERE id = '$resumptionID'";
      } elseif ($position == 'aaro') {
        $sql = "UPDATE resumption_temporary
                SET aaroComment = '$tempComment'
                WHERE id = '$resumptionID'";
      } elseif ($position == 'registrar') {
        $sql = "UPDATE resumption_temporary
                SET registrarAcknowledge = '$tempAckResult',
                registrarComment = '$tempComment'
                WHERE id = '$resumptionID'";
      }  

    $result = $conn->query($sql);
  } else{

  if($position == 'deanOrHod') {
      $sql = "INSERT INTO resumption_temporary 
              (id, deanOrHeadComment)
              VALUES ('$resumptionID','$tempComment')";
  }elseif($position == 'afo') {
    $sql = "INSERT INTO resumption_temporary 
            (id, afoFees, afoComment)
            VALUES ('$resumptionID', '$tempFees', '$tempComment')";
  }elseif($position == 'aaro') {
    $sql = "INSERT INTO resumption_temporary 
            (id, aaroComment)
            VALUES ('$resumptionID', '$tempComment')";
  }elseif($position == 'registrar') {
    $sql = "INSERT INTO resumption_temporary 
            (id, registrarAcknowledge, registrarComment)
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
    if($position == 'afo'){
      $fees = $_POST['fees'];
    }

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $acknowledgeDate=date("Y-m-d"); 

    if($position == 'deanOrHod'){
      $sql = "UPDATE resumption_of_studies_record
      SET deanOrHeadComment = '$comment',
          deanOrHeadSignature = '1',
          deanOrHeadID = '$userid',
          deanOrHeadAcknowledgeDate = '$acknowledgeDate'
      WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    } elseif($position =='afo'){
      $sql = "UPDATE resumption_of_studies_record
      SET afoFees = '$fees',
        afoComment = '$comment',
        afoSignature = '1',
        afoID = '$userid',
        afoAcknowledgeDate = '$acknowledgeDate'
      WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    } elseif($position =='aaro'){
      $sql = "UPDATE resumption_of_studies_record
      SET aaroComment = '$comment',
        aaroSignature = '1',
        aaroID = '$userid',
        aaroAcknowledgeDate = '$acknowledgeDate'
        WHERE resumptionID = '$resumptionID'";
      $result=$conn->query($sql);
    } elseif($position =='registrar'){
      $sql = "UPDATE resumption_of_studies_record
      SET registrarComment = '$comment',
        registrarSignature = '1',
        registrarAcknowledge = '$ackResult',
        registrarID = '$userid',
        registrarAcknowledgeDate = '$acknowledgeDate'
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
  function edit() {
    location.href = 'editApplicationInfo.php?resumptionID=<?php echo $resumptionID; ?>&status=<?php echo $status; ?>';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Resumption of Studies Application</h3>
  </div>
    <div class="row" style="margin:20px; margin-top:15px">
    <label for="" class="form-label">Application Details</label>
    <?php 
    if($position == 'aaro' && $status == 'Review'){ ?>
    <button style="padding: 0px; padding-left: 8px;" class="btn" onclick = "edit()"><i class="fa fa-edit"></i></button>

    <?php
    }
    ?>
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
    if($status == 'Review'){

      if($deanOrHeadSignature == 1){
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>
        <table class="table">  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table> <?php
        }
      }

      if($afoSignature == 1){
        echo '<label for="" class="form-label" >Account & Finance Office</label>';
        $sql3 = "SELECT administrator.name AS name, afoID AS id, afoComment AS comment, afoAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.afoID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>

        <table class="table">  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table>
      <?php
        }
      }
      if($aaroSignature == 1){
        echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office</label>';
        $sql3 = "SELECT administrator.name AS name, aaroID AS id, aaroComment AS comment, aaroAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.aaroID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>

        <table class="table">  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table>
      <?php
        }
      }
    
      if($position == 'deanOrHod'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Faculty (Head of Department / Dean) Use</b></label>';
      } elseif($position =='afo'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>Account & Finance Office Use</b></label>';
      } elseif($position =='aaro'){ 
        echo '<label class="form-label" style="margin-top: 30px;margin-left: 20px; font-size: 110%"><b>Academic Affairs, Admission & Registration Office  Use</b></label>';  
      }  elseif($position =='registrar'){ 
        echo '<label class="form-label" style="margin-top: 30px;margin-left: 20px; font-size: 110%"><b>Academic Affairs, Admission & Registration Office  Use (Registrar)</b></label>';  
      } 
      ?>
      <form  action="reviewResumption.php" method="post" enctype="multipart/form-data">
      <?php if($position == 'afo'){ ?>
        <div class="row" style="margin: 20px; margin-left: 5px;">
        <label for="comment" class="col-md-1 col-form-label">Fees:</label>
        <input type="text" id="fees" name="fees" value="<?php
               if ($resultTemp->num_rows > 0) {
              if($tempAfoFees != null){
                echo $tempAfoFees;} }
                ?>" required> 
          </div>
        <?php } ?>
        <div class="row" style="margin: 20px; margin-left: 5px;">
          <label for="comment" class="col-md-1 col-form-label">Comments:</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave a comment here" name="comment" id="comment" required><?php
            if ($resultTemp->num_rows > 0) {
              if($position == 'deanOrHod' && $tempDeanOrHeadComment != null){
                echo $tempDeanOrHeadComment;} 
              elseif($position == 'afo' && $tempAfoComment != null){
                echo $tempAfoComment;} 
              elseif($position == 'aaro' && $tempAaroComment != null){
                echo $tempAaroComment;} 
              elseif($position == 'registrar' && $tempRegistrarComment != null){
                echo $tempRegistrarComment;} 
              }
              ?></textarea>
          </div>
        </div>
        <?php if($position == 'registrar'){ ?>
          <div class="row" style="margin: 20px; margin-left: 20px;">
          <label for="id" class="form-label" style="margin-top: 3px;">Desicion:</label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="decision" id="ack" value="ack"checked>
            <label class="form-check-label" for="inlineRadio1">Approved</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="decision" id="notAck" value="notAck"
            <?php
            if ($resultTemp->num_rows > 0) {
            if ($position == 'registrar' && $tempRegistrarAcknowledge == 0 && $tempRegistrarAcknowledge != null) {
                echo 'checked';
            }}
            ?>>
            <label class="form-check-label" for="inlineRadio2">Disapproved</label>
          </div>
          </div>
        <?php } ?>
        <div class="row" style="margin: 20px;">
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Personal Data Protection Act (PDPA)</strong></label></td>
          </tr>
        </table>
        <p>I understand and agree that Southern University College has the permission to use my personal data for the purpose of administering. I have read, understand and agreed to the Personal Data Protection Act of Southern University College. <br> (Note: You may access and update your personal data by writing to us at <a href="mailto:reg@sc.edu.my">reg@sc.edu.my</a>)</p>
        </div>
        <input type="hidden" name="resumptionID" value="<?php echo $resumptionID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px; float:right;";>Submit</button>
        <button name="save" type="submit" class="btn btn-outline-info" style="margin-left:20px; float:right;" onclick="showSuccessMessage()";>Save</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
      </form>
      <?php
    
    }elseif($status == 'Completed' || $status == 'Approved' || $status == 'Disapproved'){ 

      if($deanOrHeadSignature == 1){
        echo '<label for="" class="form-label" >Faculty (Head of Department / Dean)</label>';
        $sql1 = "SELECT lecturer.name AS name, deanOrHeadID AS id, deanOrHeadComment AS comment, deanOrHeadAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join lecturer on resumption_of_studies_record.deanOrHeadID=lecturer.lecturerID WHERE resumptionID = '$resumptionID'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>
        <table class="table">  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table> <?php
        }
      }

      if($afoSignature == 1){
        echo '<label for="" class="form-label" >Account & Finance Office</label>';
        $sql3 = "SELECT administrator.name AS name, afoID AS id, afoComment AS comment, afoAcknowledgeDate AS acknowledgeDate, afoFees AS fees FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.afoID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $fees=$row['fees'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>

        <table class="table"> 
          <tr>
            <th class="thReview">Fees</th><td class="table-light " colspan="3"><?php echo $fees; ?></td>
          </tr>  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table>
      <?php
        }
      }

      if($aaroSignature == 1){
        echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office</label>';
        $sql3 = "SELECT administrator.name AS name, aaroID AS id, aaroComment AS comment, aaroAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.aaroID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
            $name=$row['name'];
            $id=$row['id'];
            $comment=$row['comment'];
            $acknowledgeDate=$row['acknowledgeDate'];
          }
          ?>

        <table class="table">  
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table>
      <?php
        } 
      }   

      if($registrarSignature == 1){
        echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office (Registrar)</label>';
        $sql3 = "SELECT administrator.name AS name, registrarID AS id, registrarAcknowledge AS decision, registrarComment AS comment, registrarAcknowledgeDate AS acknowledgeDate FROM resumption_of_studies_record left join administrator on resumption_of_studies_record.registrarID=administrator.administratorID WHERE resumptionID = '$resumptionID'";
        $result3 = $conn->query($sql3);
      
        if ($result3->num_rows > 0) {
          while ($row = $result3->fetch_assoc()) {
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
            <th class="thReview">Decision</th><td class="table-light " colspan="3"><?php echo $acknowledge; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light " colspan="3"><?php echo $comment; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">ID</th><td class="table-light"><?php echo $id; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Date</th><td class="table-light " colspan="3"><?php echo $acknowledgeDate; ?></td>
          </tr> 
      </table> <?php
        } 
      } ?>
      </div>
      <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-right:20px; float:right;" onclick="back()";>Back</button>
      <?php   
    }?>

  </div>
  </body>
</html>