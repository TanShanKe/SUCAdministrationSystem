<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["deanOrHod", "aaro", "afo", "lib", "sao", "iso", "sro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$defermentID = $_GET['defermentID'];
$status = $_GET['status'];
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


//Check is the temporary data exist
$sql = "SELECT * FROM deferment_temporary WHERE defermentID = '$defermentID'";
$resultTemp = $conn->query($sql);

if ($resultTemp->num_rows > 0) {
    $row = $resultTemp->fetch_assoc();
    $tempIsoRemarks = $row['tempIsoRemarks']; 
    $tempScholarship = $row['tempScholarship']; 
    $tempSaoRemarks = $row['tempSaoRemarks']; 
    $tempCounselingRemarks = $row['tempCounselingRemarks']; 
    $tempReturnedDocument = $row['tempReturnedDocument']; 
    $tempSroRemarks = $row['tempSroRemarks']; 
    $tempOverdueBooks = $row['tempOverdueBooks']; 
    $tempLibRemarks = $row['tempLibRemarks']; 
    $tempFeesOverdue = $row['tempFeesOverdue']; 
    $tempFees = $row['tempFees']; 
    $tempReturnedDeposit = $row['tempReturnedDeposit']; 
    $tempAfoRemarks = $row['tempAfoRemarks']; 
    $tempHodRemarks = $row['tempHodRemarks']; 
    $tempAaroRemarks = $row['tempAaroRemarks']; 
    $tempRegistrarRemarks = $row['tempRegistrarRemarks']; 
    $tempRegistrarDecision = $row['tempRegistrarDecision']; 
}

//Save temporary data
if (isset($_POST['save'])) {
  $defermentID = $_POST['defermentID'];
  $tempRemarks = $_POST['remarks'];

  if(($position == 'sao' && $saoSignature == 0) || $position == 'sro' || $position == 'lib' || $position == 'afo' || ($position == 'afo' && $saoSignature == 0)){
    $tempDecision = $_POST['decision'];
    if ($tempDecision == 'yes') {
        $tempResult = 1;
    } elseif ($tempDecision == 'no') {
        $tempResult = 0;
    }
  }

  if($position == 'afo'){
    $tempFee = $_POST['fees'];
    $tempDecision2 = $_POST['decision2'];
    if ($tempDecision2 == 'yes') {
        $tempResult2 = 1;
    } elseif ($tempDecision2 == 'no') {
        $tempResult2 = 0;
    }
  }

  $sql = "SELECT deferment_temporary.defermentID, tempIsoRemarks, tempScholarship, tempSaoRemarks, tempCounselingRemarks, tempReturnedDocument, tempSroRemarks, tempOverdueBooks, tempLibRemarks, tempFeesOverdue, tempFees, tempReturnedDeposit, tempAfoRemarks,tempHodRemarks, tempAaroRemarks, tempRegistrarRemarks, tempRegistrarDecision, saoSignature, aaroSignature FROM deferment_temporary LEFT JOIN deferment_record ON deferment_record.defermentID=deferment_temporary.defermentID WHERE deferment_temporary.defermentID = '$defermentID'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tempIsoRemarks = $row['tempIsoRemarks']; 
      $tempScholarship = $row['tempScholarship']; 
      $tempSaoRemarks = $row['tempSaoRemarks']; 
      $tempCounselingRemarks = $row['tempCounselingRemarks']; 
      $tempReturnedDocument = $row['tempReturnedDocument']; 
      $tempSroRemarks = $row['tempSroRemarks']; 
      $tempOverdueBooks = $row['tempOverdueBooks']; 
      $tempLibRemarks = $row['tempLibRemarks']; 
      $tempFeesOverdue = $row['tempFeesOverdue']; 
      $tempFees = $row['tempFees']; 
      $tempReturnedDeposit = $row['tempReturnedDeposit']; 
      $tempAfoRemarks = $row['tempAfoRemarks']; 
      $tempHodRemarks = $row['tempHodRemarks']; 
      $tempAaroRemarks = $row['tempAaroRemarks']; 
      $tempRegistrarRemarks = $row['tempRegistrarRemarks']; 
      $tempRegistrarDecision = $row['tempRegistrarDecision']; 
      $saoSignature = $row['saoSignature']; 
      $aaroSignature = $row['aaroSignature']; 


      // Check if updating an existing temporary record
      if ($position == 'iso') {
        $sql = "UPDATE deferment_temporary
                SET tempIsoRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'sao' && $saoSignature == 0) {
        $sql = "UPDATE deferment_temporary
                SET tempScholarship = '$tempResult',
                tempSaoRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'sao') {
        $sql = "UPDATE deferment_temporary
                SET tempcounselingRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'sro' ) {
        $sql = "UPDATE deferment_temporary
                SET tempReturnedDocument = '$tempResult',
                tempSroRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'lib' ) {
        $sql = "UPDATE deferment_temporary
                SET tempOverdueBooks = '$tempResult',
                tempLibRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'afo' ) {
        $sql = "UPDATE deferment_temporary
                SET tempFeesOverdue = '$tempResult',
                tempFees = '$tempFee',
                tempAfoRemarks = '$tempRemarks',
                tempReturnedDeposit = '$tempResult2'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'hod' ) {
        $sql = "UPDATE deferment_temporary
                SET tempHodRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'aaro' && $aaroSignature == 0 ) {
        $sql = "UPDATE deferment_temporary
                SET tempAaroRemarks = '$tempRemarks'
                WHERE defermentID = '$defermentID'";
      }elseif ($position == 'aaro') {
        $sql = "UPDATE deferment_temporary
                SET tempRegistrarRemarks = '$tempRemarks',
                tempRegistrarDecision = '$tempResult'
                WHERE defermentID = '$defermentID'";
      }

    $result = $conn->query($sql);
  } else{

  if($position == 'iso') {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempIsoRemarks)
            VALUES ('$defermentID', '$tempRemarks')";
  }elseif ($position == 'sao' && $saoSignature == 0) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempScholarship, tempSaoRemarks )
            VALUES ('$defermentID', '$tempDecision', '$tempRemarks')";
  }elseif ($position == 'sao') {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, counselingRemarks)
            VALUES ('$defermentID', '$tempRemarks')";
  }elseif ($position == 'sro' ) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempReturnedDocument, tempSroRemarks )
            VALUES ('$defermentID', '$tempDecision', '$tempRemarks')";
  }elseif ($position == 'lib' ) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempOverdueBooks, tempLibRemarks )
            VALUES ('$defermentID', '$tempDecision', '$tempRemarks')";
  }elseif ($position == 'afo' ) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempFeesOverdue, tempFees, tempAfoRemarks, tempReturnedDeposit )
            VALUES ('$defermentID', '$tempDecision', '$tempFee', '$tempRemarks', '$tempDecision2')";
  }elseif ($position == 'hod' ) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempHodRemarks)
            VALUES ('$defermentID', '$tempRemarks')";
  }elseif ($position == 'aaro' && $aaroSignature == 0 ) {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempAaroRemarks)
            VALUES ('$defermentID', '$tempRemarks')";
  }elseif ($position == 'aaro') {
    $sql = "INSERT INTO deferment_temporary 
            (defermentID, tempRegistrarRemarks, tempRegistrarDecision)
            VALUES ('$defermentID', '$tempRemarks',  '$tempDecision')";
  }
    $result = $conn->query($sql);
}

if ($result === TRUE) {
  echo '<script type="text/javascript">';
  echo 'alert("Record saved successfully!");'; 
  echo 'window.location = "viewDefermentApplied.php";';
  echo '</script>';
} else {
  echo "Error: " . $conn->error;
}

}

 
  // Submit the review data
  if(isset($_POST['submit'])){
    $defermentID = $_POST['defermentID'];

    $remarks = $_POST['remarks'];

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date=date("Y-m-d"); 

    $sql2 = "SELECT saoSignature, counselingSignature FROM deferment_record WHERE defermentID = '$defermentID'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $saoSignature=$row['saoSignature'];
            $counselingSignature=$row['counselingSignature'];

        if(($position =='sao' && $saoSignature == 0)){
          $decision = $_POST['decision'];
          if ($decision == 'yes') {
              $decisionResult = 1;
          } elseif ($decision == 'no') {
              $decisionResult = 0;
          }
        } 

        if($position == 'sao'){   
          if($saoSignature == 0){
              $sql = "UPDATE deferment_record
              SET scholarship = '$decisionResult',
                  saoRemarks = '$remarks',
                  saoSignature = '1',
                  saoID = '$userid',
                  saoDate = '$date'
              WHERE defermentID = '$defermentID'";
              $result=$conn->query($sql);
          }elseif($counselingSignature == 0){
              $sql = "UPDATE deferment_record
              SET counselingRemarks = '$remarks',
                  counselingSignature = '1',
                  counselingID = '$userid',
                  counselingDate = '$date'
              WHERE defermentID = '$defermentID'";
              $result=$conn->query($sql);
          }
      }
      }
    }  

    if($position =='sro' || $position =='lib'){
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

    if($position == 'iso'){
      $sql = "UPDATE deferment_record
      SET isoRemarks = '$remarks',
          isoSignature = '1',
          isoID = '$userid',
          isoDate = '$date'
      WHERE defermentID = '$defermentID'";
      $result=$conn->query($sql);
    } elseif($position == 'sro'){
        $sql = "UPDATE deferment_record
        SET returnedDocument = '$decisionResult',
            sroRemarks = '$remarks',
            sroSignature = '1',
            sroID = '$userid',
            sroDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position == 'lib'){
        $sql = "UPDATE deferment_record
        SET overdueBooks = '$decisionResult',
            libRemarks = '$remarks',
            libSignature = '1',
            libID = '$userid',
            libDate = '$date'
        WHERE defermentID = '$defermentID'";
        $result=$conn->query($sql);
    } elseif($position == 'afo'){
        $sql = "UPDATE deferment_record
        SET feesOverdue = '$decisionResult',
            fees = '$fees',
            afoRemarks = '$remarks',
            returnedDeposit = '$decisionResult2',
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
    } 

    $sql3 = "SELECT aaroSignature, registrarSignature FROM deferment_record WHERE defermentID = '$defermentID'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            $aaroSignature=$row['aaroSignature'];
            $registrarSignature=$row['registrarSignature'];

        if(($position =='aaro' && $aaroSignature == 1)){
          $decision = $_POST['decision'];
          if ($decision == 'yes') {
              $decisionResult = 1;
          } elseif ($decision == 'no') {
              $decisionResult = 0;
          }
        } 

        if($position == 'aaro'){   
          if($aaroSignature == 0){
            $sql = "UPDATE deferment_record
            SET aaroRemarks = '$remarks',
                aaroSignature = '1',
                aaroID = '$userid',
                aaroDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
          }elseif($registrarSignature == 0){
            $sql = "UPDATE deferment_record
            SET registrarRemarks = '$remarks',
                registrarSignature = '1',
                registrarDecision = '$decisionResult',
                registrarID = '$userid',
                registrarDate = '$date'
            WHERE defermentID = '$defermentID'";
            $result=$conn->query($sql);
          }
      }
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
      location.href = 'viewDefermentApplied.php';
    }
  }
  function back() {
    location.href = 'viewDefermentApplied.php';
  }
</script>

<script type='text/javascript'>
function toggleInput() {
    var yes = document.getElementById("yes");
    var no = document.getElementById("no");

    if (yes.checked) {
      existingDay.disabled = false;
    } else if(no.checked){
      existingDate.disabled = true;
    }
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

    if($status == 'Review'){
      ?><form  action="reviewDeferment.php" method="post" enctype="multipart/form-data"><?php
      if($position == 'iso'){
        echo '<label class="form-label" style="margin-top: 30px; margin-left: 20px; font-size: 110%"><b>International Student Office</b></label>';
      } elseif($position == 'sao' && $saoSignature == 0){ ?>
        <div class="row" style="margin: 20px; margin-top:10px">
        <label for="id" class="form-label" style="margin-top: 3px;">Scholarship Holder:</label>
        <div class="form-check form-check-inline" style="margin-left: 30px;">
          <input class="form-check-input" type="radio" name="decision" id="yes" value="yes"checked>
          <label class="form-check-label" for="inlineRadio1">Yes</label>
        </div>
        <div class="form-check form-check-inline" style="margin-left: 10px;">
          <input class="form-check-input" type="radio" name="decision" id="no" value="no"
          <?php
            if ($resultTemp->num_rows > 0) {
            if ($tempScholarship == 0 && $tempScholarship != null) {
                echo 'checked';
            }}
            ?>>
          <label class="form-check-label" for="inlineRadio2">No</label>
        </div>
      </div>
      <?php }
       elseif($position == 'sro'){ ?>
      <div class="row" style="margin: 20px; margin-top:10px">
        <label for="id" class="form-label" style="margin-top: 3px;">Returned document:</label>
        <div class="form-check form-check-inline" style="margin-left: 30px;">
          <input class="form-check-input" type="radio" name="decision" id="yes" value="yes"checked>
          <label class="form-check-label" for="inlineRadio1">Yes</label>
        </div>
        <div class="form-check form-check-inline" style="margin-left: 10px;">
          <input class="form-check-input" type="radio" name="decision" id="no" value="no"
          <?php
            if ($resultTemp->num_rows > 0) {
            if ($tempReturnedDocument == 0 && $tempReturnedDocument != null) {
                echo 'checked';
            }}
            ?>>
          <label class="form-check-label" for="inlineRadio2">No</label>
        </div>
      </div>
      <?php }
       elseif($position == 'lib'){ ?>
        <div class="row" style="margin: 20px; margin-top:10px">
          <label for="id" class="form-label" style="margin-top: 3px;">Overdue books:</label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="decision" id="yes" value="yes"checked>
            <label class="form-check-label" for="inlineRadio1">Yes</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="decision" id="no" value="no"
            <?php
            if ($resultTemp->num_rows > 0) {
            if ($tempOverdueBooks == 0 && $tempOverdueBooks != null) {
                echo 'checked';
            }}
            ?>>
            <label class="form-check-label" for="inlineRadio2">No</label>
          </div>
        </div>
        <?php }
        elseif($position == 'afo'){ ?>
          <div class="row" style="margin: 20px; margin-top:10px">
            <label for="id" class="form-label" style="margin-top: 3px;">School fees overdue:</label>
            <div class="form-check form-check-inline" style="margin-left: 30px;">
              <input class="form-check-input" type="radio" name="decision" id="yes" value="yes"checked>
              <label class="form-check-label" for="inlineRadio1">Yes</label>
              <label style="margin-left: 20px; margin-top:7px; margin-right:2px;" >RM</label>
              <input type="text" id="fees" name="fees" value="<?php
               if ($resultTemp->num_rows > 0) {
              if($tempFees != null){
                echo $tempFees;} }
                ?>"> 
            </div>
            <div class="form-check form-check-inline" style="margin-left: 10px;">
              <input class="form-check-input" type="radio" name="decision" id="no" value="no"
              <?php
              if ($resultTemp->num_rows > 0) {
              if ($tempFeesOverdue == 0 && $tempFeesOverdue != null) {
                  echo 'checked';
              }}
              ?>>
              <label class="form-check-label" for="inlineRadio2">No</label>
            </div>
          </div>
          <?php }
          ?>
        <div class="row" style="margin: 20px;">
          <label for="remarks" style="margin-right:10px">Remarks / Suggestions:</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave remarks / suggestions here" name="remarks" id="remarks"><?php
            if ($resultTemp->num_rows > 0) {
              if($position == 'iso' && $tempIsoRemarks != null){
                echo $tempIsoRemarks;} 
              elseif($position == 'sao' && $saoSignature == 0 && $tempSaoRemarks != null){
                echo $tempSaoRemarks;} 
              elseif($position == 'sao' && $tempCounselingRemarks != null){
                echo $tempCounselingRemarks;} 
              elseif($position == 'sro' && $tempSroRemarks != null){
                echo $tempSroRemarks;} 
              elseif($position == 'lib' && $tempLibRemarks != null){
                echo $tempLibRemarks;} 
              elseif($position == 'afo' && $tempAfoRemarks != null){
                echo $tempAfoRemarks;} 
              elseif($position == 'hod' && $tempHodRemarks != null){
                echo $tempHodRemarks;} 
              elseif($position == 'aaro' && $aaroSignature == 0 && $tempAaroRemarks != null){
                echo $tempAaroRemarks;} 
              elseif($position == 'aaro' && $tempRegistrarDecision != null){
                echo $tempRegistrarDecision;}      
              }
              ?></textarea>
          </div>
        </div>
        <?php if($position == 'afo'){ ?>
          <div class="row" style="margin: 20px; margin-top:10px">
            <label for="id" class="form-label" style="margin-top: 3px;">Returned Deposit:</label>
            <div class="form-check form-check-inline" style="margin-left: 30px;">
              <input class="form-check-input" type="radio" name="decision2" id="yes" value="yes"checked>
              <label class="form-check-label" for="inlineRadio1">Yes</label>
            </div>
            <div class="form-check form-check-inline" style="margin-left: 10px;">
              <input class="form-check-input" type="radio" name="decision2" id="no" value="no"
              <?php
              if ($resultTemp->num_rows > 0) {
              if ($tempReturnedDeposit == 0 && $tempReturnedDeposit != null) {
                  echo 'checked';
              }}
              ?>>
              <label class="form-check-label" for="inlineRadio2">No</label>
            </div>
          </div> 
          <?php } elseif($position == 'aaro' && $aaroSignature == 1){ ?>
          <div class="row" style="margin: 20px; margin-top:10px">
            <label for="id" class="form-label" style="margin-top: 3px;">Decision:</label>
            <div class="form-check form-check-inline" style="margin-left: 30px;">
              <input class="form-check-input" type="radio" name="decision" id="yes" value="yes"checked>
              <label class="form-check-label" for="inlineRadio1">Yes</label>
            </div>
            <div class="form-check form-check-inline" style="margin-left: 10px;">
              <input class="form-check-input" type="radio" name="decision" id="no" value="no"
              <?php
              if ($resultTemp->num_rows > 0) {
              if ($tempRegistrarDecision == 0 && $tempRegistrarDecision != null) {
                  echo 'checked';
              }}
              ?>>
              <label class="form-check-label" for="inlineRadio2">No</label>
            </div>
          </div>
          <?php }
          ?>
        <div class="row" style="margin: 20px;">
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        </div>
        <input type="hidden" name="defermentID" value="<?php echo $defermentID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px;";>Submit</button>
        <button name="save" type="submit" class="btn btn-info" style="margin-left:20px;" onclick="showSuccessMessage()";>Save</button>
        <button name="cancel" type="button" class="btn btn-secondary" style="margin-left:20px;" onclick="confirmCancel()";>Cancel</button>
      </form>
      <?php
    }elseif($status == 'Completed'){ ?>

      <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
      
    <?php } ?>
  
    </div>
  </body>
</html>