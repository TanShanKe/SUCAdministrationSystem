<?php
include '../config.php';

session_start();

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$resumptionID = $_GET['resumptionID'];

$sql = "SELECT student.name AS name, student.batchNo AS batchNo, applicantID, applicationDate, yearOfResumption, semOfResumption, yearOfDeferment, semOfDeferment FROM resumption_of_studies_record left join student on resumption_of_studies_record.applicantID=student.studentID WHERE resumptionID = '$resumptionID'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
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

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function back() {
    location.href = 'viewResumption.php';
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
  <h3 style="margin-right: 20px">Resumption of Studies Application Result</h3>
  </div>
    <div class="row" style="margin:20px; margin-top:15px">
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
    }

    ?>

    </div>
    <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-right:20px; float: right;" onclick="back()";>Back</button>
  </div>

  </body>
</html>

