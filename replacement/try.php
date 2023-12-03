<?php
include '../config.php';

session_start(); // Start the session

$allowedPositions = ["lecturer"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)){
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];

if(isset($_POST['apply'])){

  date_default_timezone_set('Asia/Kuala_Lumpur');

  // Generate id
  $currentYear = date("y"); 
  $currentMonth = date("m"); 
  // Use a separate file for each month
  $counterFile = "id_counter_" . $currentYear . $currentMonth . ".txt"; 
  if (!file_exists($counterFile)) {
      file_put_contents($counterFile, "1");
  }
  $currentCounter = intval(file_get_contents($counterFile));
  $generatedId = $currentYear . $currentMonth . str_pad($currentCounter, 3, "0", STR_PAD_LEFT);
  $currentCounter++;
  file_put_contents($counterFile, $currentCounter);

  $typeOfChange = $_POST['typeOfChange'];
    if ($typeOfChange == 'replacement') {
        $type = 1;
    } elseif ($typeOfChange == 'permanent') {
        $type = 0;
    }
  $subjectCode=$_POST['subjectCode']; 
  $lecturerID=$_POST['lecturerID']; 
  $existingDate=$_POST['existingDate'];
  $existingDay=$_POST['existingDay'];
  $existingStartTime=$_POST['existingStartTime'];
  $existingEndTime=$_POST['existingEndTime'];
  $existingVenue=$_POST['existingVenue'];
  $newDate=$_POST['newDate'];
  $newDay=$_POST['newDay'];
  $newStartTime=$_POST['newStartTime'];
  $newEndTime=$_POST['newEndTime'];
  $newVenue=$_POST['newVenue'];
  $reason=$_POST['reason'];
  $applicationDate=date("Y-m-d"); 

  $sql = "INSERT INTO change_class_record (changeClassID, subjectCode, lecturerID, typeOfChange, existingDate, existingDay, existingStartTime, existingEndTime, existingVenue, newDate, newDay, newStartTime, newEndTime, newVenue, reason, applicationDate, applicantID, applicantSignature) VALUES ('$generatedId', '$subjectCode', '$lecturerID', '$type', '$existingDate', '$existingDay', '$existingStartTime', '$existingEndTime', '$existingVenue', '$newDate' , '$newDay' , '$newStartTime' , '$newEndTime' , '$newVenue' ,  '$reason', '$applicationDate', '$userid', '1')";
  $result=$conn->query($sql);

  if ($result === TRUE) {
    echo '<script type="text/javascript">';
    echo 'alert("Your application successfully submitted!");'; 
    echo 'window.location = "viewReplacement.php";';
    echo '</script>';
  } else {
      echo "Error: " . $conn->error;
  }
  
  }

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";

$sql = "SELECT subjectCode, name FROM subject";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $subjectCode = $row['subjectCode'];
  $subjectName = $row['name'];
  $subject = $subjectName . ' ' . $subjectCode;
  $subjects[] = array("subjectCode" => $subjectCode, "val" => $subject);
}

$query = "SELECT lecturer.lecturerID AS lecturerID, name, subject_lecturer.subjectCode AS subjectCode FROM lecturer LEFT JOIN subject_lecturer ON lecturer.lecturerID=subject_lecturer.lecturerID";
$result = $conn->query($query);

while($row = $result->fetch_assoc()){
  $subjectCode = $row['subjectCode'];
  $lecturerName = $row['name'];
  $lecturerID = $row['lecturerID'];
  $lecturers[$subjectCode][] = array("lecturerID" => $lecturerID, "val" => $lecturerName);
}

$jsonSubjects = json_encode($subjects);
$jsonLecturers = json_encode($lecturers);

?>

<script type='text/javascript'>
  <?php
    echo "var subjects = $jsonSubjects; \n";
    echo "var lecturers = $jsonLecturers; \n";
  ?>
  function loadSubjects(){
    var select = document.getElementById("subjectsSelect");
    select.onchange = updateLecturers;
    select.options[0] = new Option('Select the subject'); 
    lecturersSelect.options[0] = new Option('Select the lecturer'); 
    for(var i = 0; i < subjects.length; i++){
      select.options[i+1] = new Option(subjects[i].val, subjects[i].subjectCode);
    }
}
  function updateLecturers(){
    var subjectCode = this.value;
    var lecturersSelect = document.getElementById("lecturersSelect");
    lecturersSelect.options.length = 0; // Delete all options if any are present
    lecturersSelect.options[0] = new Option('Select the lecturer'); 
    if (subjectCode != 'Select the subject'){
      for(var i = 0; i < lecturers[subjectCode].length; i++){
        lecturersSelect.options[i] = new Option(lecturers[subjectCode][i].val, lecturers[subjectCode][i].lecturerID);
    }
    }  
  }

  function toggleInput() {
    var replacement = document.getElementById("replacement");
    var permanent = document.getElementById("permanent");
    var textInput = document.getElementById("textInput");

    if (replacement.checked) {
      existingDay.disabled = true;
      existingDate.disabled = false;
      newDay.disabled = true;
      newDate.disabled = false;
    } else if(permanent.checked){
      existingDate.disabled = true;
      existingDay.disabled = false;
      newDate.disabled = true;
      newDay.disabled = false;
    }
  }

  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewReplacement.php';
    }
  }
</script>

  <body onload='loadSubjects()'>
  <div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Replacement/ Permanent Change of Class Room Venue/ Time Application Form</center></h3>
      <div class="row" style="margin: 20px;">
        <label for="type" class="form-label" style="margin-top: 3px;">Type Of Change: </label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="typeOfChange" id="replacement" value="replacement" onclick="toggleInput()" checked>
            <label class="form-check-label" for="inlineRadio1">Class Replacement</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="typeOfChange" id="permanent" value="permanent" onclick="toggleInput()">
            <label class="form-check-label" for="inlineRadio2">Permanent</label>
          </div>
      </div>
      <div class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Subject Code & Name: </label>
    <select name='subjectCode' id='subjectsSelect'>
    </select>
    </div>
      <div class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Lecturer Name: </label>
    <select name='lecturerID' id='lecturersSelect'>
    </select>
    </div>
    <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Existing details</b></label> 
      </div>
    <div class="row" style="margin: 20px;">
        <label for="existingDate" id="existingDateLb"class="form-label" style="margin-top: 5px; margin-right: 30px;">Existing Date: </label>
        <input type="date" id="existingDate" name="existingDate">
    </div>
    <div class="row" style="margin: 20px;">
      <label for="existingDay" id="existingDayLb" class="form-label" style="margin-top: 5px; margin-right: 30px;">Existing Day: </label>
      <select name="existingDay" id="existingDay" disabled>
      <option value="Monday">Monday</option>
      <option value="Tuesday">Tuesday</option>
      <option value="Wednesday">Wednesday</option>
      <option value="Thursday">Thursday</option>
      <option value="Friday">Friday</option>
      <option value="Saturday">Saturday</option>
      <option value="Sunday">Sunday</option>
      </select>
    </div>
    <div class="row" style="margin: 20px;">
        <label for="existingStartDate" class="form-label" style="margin-top: 5px; margin-right: 30px;">Existing Start Time: </label>
        <input type="time" id="existingStartTime" name="existingStartTime">
    </div>
    <div class="row" style="margin: 20px;">
        <label for="existingEndDate" class="form-label" style="margin-top: 5px; margin-right: 30px;">Existing End Time: </label>
        <input type="time" id="existingEndTime" name="existingEndTime">
    </div>
    <div class="row" style="margin: 20px;">
        <label for="existingVenue" class="form-label" style="margin-top: 5px; margin-right: 30px;">Existing Venue: </label>
        <input type="text" id="existingVenue" name="existingVenue">
    </div>
    <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>New details</b></label> 
      </div>
    <div class="row" style="margin: 20px;">
        <label for="newDate" id="newDateLb" class="form-label" style="margin-top: 5px; margin-right: 30px;">New Date: </label>
        <input type="date" id="newDate" name="newDate">
    </div>
    <div class="row" style="margin: 20px;">
      <label for="newDay" id="newDayLb" class="form-label" style="margin-top: 5px; margin-right: 30px;">New Day: </label>
      <select name="newDay" id="newDay" disabled>
      <option value="Monday">Monday</option>
      <option value="Tuesday">Tuesday</option>
      <option value="Wednesday">Wednesday</option>
      <option value="Thursday">Thursday</option>
      <option value="Friday">Friday</option>
      <option value="Saturday">Saturday</option>
      <option value="Sunday">Sunday</option>
      </select>
    </div>
    <div class="row" style="margin: 20px;">
        <label for="newStartTime" class="form-label" style="margin-top: 5px; margin-right: 30px;">New Start Time: </label>
        <input type="time" id="newStartTime" name="newStartTime">
    </div>
    <div class="row" style="margin: 20px;">
        <label for="newEndTime" class="form-label" style="margin-top: 5px; margin-right: 30px;">New End Time: </label>
        <input type="time" id="newEndTime" name="newEndTime">
    </div>
    <div class="row" style="margin: 20px;">
        <label for="newVenue" class="form-label" style="margin-top: 5px; margin-right: 30px;">New Venue: </label>
        <input type="text" id="newVenue" name="newVenue">
    </div>
    <label for="reason" class="col-md-1 col-form-label">Reason(s):</label>
          <div class="col-md-10">
            <textarea class="form-control" placeholder="Leave your reason here" name="reason" id="reason"></textarea>
          </div>
      <br>
      <div class="row" style="margin: 20px;">
      <table>
        <tr>
          <td style="vertical-align: top;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
          <td><label for="pdpa"><strong>Personal Data Protection Act (PDPA)</strong></label></td>
        </tr>
      </table>
      <p>I understand and agree that Southern University College has the permission to use my personal data for the purpose of administering. I have read, understand and agreed to the Personal Data Protection Act of Southern University College. <br> (Note: You may access and update your personal data by writing to us at <a href="mailto:reg@sc.edu.my">reg@sc.edu.my</a>)</p>
      </div>
      <button name="apply" type="submit" class="btn btn-info" style="margin-left:20px;">Apply</button>
      <button name="apply" type="button" class="btn btn-secondary" style="margin-left:20px;" onclick="confirmCancel()";>Cancel</button>
    </form>
  </div>
</div>
  </body>
</html>
