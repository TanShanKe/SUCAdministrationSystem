<?php
include '../config.php';

$resumptionID = $_GET['resumptionID'];

$sql = "SELECT student.name AS name, student.batchNo AS batchNo, applicantID, applicationDate, yearOfResumption, semOfResumption, yearOfDeferment, semOfDeferment, aaroAcknowledge, aaroComment, afoAcknowledge, afoComment, deanOrHeadAcknowledge, deanOrHeadComment FROM resumption_of_studies_record left join student on resumption_of_studies_record.applicantID=student.studentID WHERE resumptionID = '$resumptionID'";

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
    $aaroAcknowledge = $row['aaroAcknowledge'];
    $aaroComment = $row['aaroComment'];
    $afoAcknowledge = $row['afoAcknowledge'];
    $afoComment = $row['afoComment'];
    $deanOrHeadAcknowledge = $row['deanOrHeadAcknowledge'];
    $deanOrHeadComment = $row['deanOrHeadComment'];

    if ($deanOrHeadAcknowledge == 0) {
      $deanOrHeadAcknowledge = 'Not Acknowledge';
    } if ($deanOrHeadAcknowledge == 1) {
      $deanOrHeadAcknowledge = 'Acknowledge';
    } 
    if ($afoAcknowledge == 0) {
      $afoAcknowledge = 'Not Acknowledge';
    }elseif ($afoAcknowledge == 1) {
      $afoAcknowledge = 'Acknowledge';
    }
    if ($aaroAcknowledge == 0) {
      $aaroAcknowledge = 'Not Acknowledge';
    }elseif ($aaroAcknowledge == 1) {
      $aaroAcknowledge = 'Acknowledge';
    }
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
    <label for="" class="form-label" >Faculty (Head of Department / Dean)</label>
    <table class="table">  
        <tr>
          <th class="thReview">Acknowledge / <br>Not Acknowledge</th><td class="table-light"><?php echo $deanOrHeadAcknowledge; ?></td>
        </tr> 
        <tr>
          <th class="thReview">Comment / Remarks</th><td class="table-light"><?php echo $deanOrHeadComment; ?></td>
        </tr> 
    </table>
    <table class="table">  
    <label for="" class="form-label" >Account & Finance Office</label>
        <tr>
          <th class="thReview">Acknowledge / <br>Not Acknowledge</th><td class="table-light"><?php echo $afoAcknowledge; ?></td>
        </tr> 
        <tr>
          <th class="thReview">Comment / Remarks</th><td class="table-light"><?php echo $afoComment; ?></td>
        </tr>  
    </table>
    <table class="table"> 
    <label for="" class="form-label" >Academic Affairs & Registration Office</label>
        <tr>
          <th class="thReview">Acknowledge / <br>Not Acknowledge</th><td class="table-light"><?php echo $aaroAcknowledge; ?></td>
        </tr>
        <tr>
          <th class="thReview">Comment / Remarks</th><td class="table-light"><?php echo $aaroComment; ?></td>
        </tr>   
    </table>
    <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
    </div>
  </div>

  </body>
</html>

