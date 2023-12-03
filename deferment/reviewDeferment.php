<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["deanOrHod", "aaro", "afo", "library", "sao", "iso", "sro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$defermentID = $_GET['defermentID'];
$status = $_GET['status'];
$status2 = $_GET['status2'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
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

/*
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
*/

/*
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
*/
 
  // Submit the review data
  if(isset($_POST['submit'])){
    $defermentID = $_POST['defermentID'];

    if($position =='sao' || $position =='sro' || $position =='library'){
        $decision = $_POST['decision'];
        if ($decision == 'yes') {
            $decisionResult = 1;
        } elseif ($decision == 'no') {
            $decisionResult = 0;
        }
    } elseif($position =='afo'){
        $decision = $_POST['decision'];
        $decision2 = $_POST['decision2'];
        $fees = $_POST['fees'];
        if ($decision == 'yes') {
            $decisionResult = 1;
        } elseif ($decision == 'no') {
            $decisionResult = 0;
        }
        if ($decision2 == 'yes') {
            $decisionResult2 = 1;
        } elseif ($decision2 == 'no') {
            $decisionResult2 = 0;
        }
    }

    $remarks = $_POST['remarks'];

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date=date("Y-m-d"); 

    if($position == 'iso'){
      $sql = "UPDATE deferment_record
      SET isoRemarks = '$remarks',
          isoSignature = '1',
          isoID = '$userid',
          isoDate = '$date'
      WHERE defermentID = '$defermentID'";
      $result=$conn->query($sql);
    } elseif($position == 'sao'){
        if($status == 'Review'){
            $sql = "UPDATE deferment_record
            SET scholarship = '$decision',
                saoRemarks = '$remarks',
                saoSignature = '1',
                saoID = '$userid',
                saoDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
        }elseif($status2 == 'Review'){
            $sql = "UPDATE deferment_record
            SET counselingRemarks = '$remarks',
                counselingSignature = '1',
                counselingID = '$userid',
                counselingDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
        }
    } elseif($position == 'sro'){
        $sql = "UPDATE deferment_record
        SET returnedDocument = '$decision',
            sroRemarks = '$remarks',
            sroSignature = '1',
            sroID = '$userid',
            sroDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position == 'library'){
        $sql = "UPDATE deferment_record
        SET overdueBooks = '$decision',
            libraryRemarks = '$remarks',
            librarySignature = '1',
            libraryID = '$userid',
            libraryDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position == 'afo'){
        $sql = "UPDATE deferment_record
        SET feesOverdue = '$decision',
            fees = '$fees',
            afoRemarks = '$remarks',
            returnedDeposit = '$decision2',
            afoSignature = '1',
            afoID = '$userid',
            afoDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position == 'deanOrHod'){
        $sql = "UPDATE deferment_record
        SET hodRemarks = '$remarks',
            hodSignature = '1',
            hodID = '$userid',
            hodDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position =='aaro'){
        if($status == 'Review'){
            $sql = "UPDATE deferment_record
            SET aaroRemarks = '$remarks',
                aaroSignature = '1',
                aaroID = '$userid',
                aaroDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
        }elseif($status2 == 'Review'){
            $sql = "UPDATE deferment_record
            SET registrarRemarks = '$remarks',
                registrarSignature = '1',
                registrarID = '$userid',
                registrarDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
        }
    }

    if ($result === TRUE) {
      echo '<script type="text/javascript">';
      echo 'alert("Successfully submitted!");'; 
      echo 'window.location = "viewDefermentApplied.php";';
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
    if($status == 'Review'){
      if($position == 'iso'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>International Student Office (For International student use only)</b></label>';
      } 

      ?>
      <form  action="reviewDeferment.php" method="post" enctype="multipart/form-data">
        <div class="row" style="margin: 20px; margin-left: 5px;">
          <label for="comment" class="col-md-1 col-form-label">Remarks / Suggestions:</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave a comment here" name="comment" id="comment"></textarea>
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
      if($position == 'aaro'){
        echo '<label for="" class="form-label" >International Student Office</label>';
        $sql = "SELECT administrator.name AS name, isoRemarks AS remarks, isoDate AS acknowledgeDate FROM deferment_record left join administrator on deferment_record.isoID=administrator.administratorID WHERE defermentID = '$defermentID'";
        $result = $conn->query($sql);
      } 

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
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    <?php
      } 
    } ?>
  
    </div>
  </body>
</html>