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
        $existingDate=$_POST['existingDate'];
        $newDate=$_POST['newDate'];
    } elseif ($typeOfChange == 'permanent') {
        $type = 0;
        $existingDay=$_POST['existingDay'];
        $newDay=$_POST['newDay'];
    }
  $subjectCode=$_POST['subjectCode']; 
  $lecturerID=$_POST['lecturerID']; 
  $existingTime=$_POST['existingTime'];
  $hour=$_POST['hour'];
  $existingVenue=$_POST['existingVenue'];
  $newTime=$_POST['newTime'];
  $newVenue=$_POST['newVenue'];
  $reason=$_POST['reason'];
  $applicationDate=date("Y-m-d"); 

  $target_dir = "uploads/";
  $totalfiles = count($_FILES['documentalProof']['name']);

  if($type = 1){
    $sql = "INSERT INTO change_class_record (changeClassID, subjectCode, lecturerID, typeOfChange, existingDate, existingTime, hour, existingVenue, newDate, newTime, newVenue, reason, applicationDate, applicantID, applicantSignature) VALUES ('$generatedId', '$subjectCode', '$lecturerID', '$type', '$existingDate', '$existingTime', '$hour', '$existingVenue', '$newDate', '$newTime' , '$newVenue' ,  '$reason', '$applicationDate', '$userid', '1')";
  }elseif($type = 0){
    $sql = "INSERT INTO change_class_record (changeClassID, subjectCode, lecturerID, typeOfChange, existingDay, existingTime, hour, existingVenue, newDay, newTime, newVenue, reason, applicationDate, applicantID, applicantSignature) VALUES ('$generatedId', '$subjectCode', '$lecturerID', '$type', '$existingDay', '$existingTime', '$hour', '$existingVenue', '$newDay' , '$newTime' , '$newVenue' ,  '$reason', '$applicationDate', '$userid', '1')";
  }

  $result=$conn->query($sql);

  for($i=0;$i<$totalfiles;$i++){
      $file_name = uniqid() . "_" . basename($_FILES["documentalProof"]["name"][$i]);
      $target_file = $target_dir . $file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["documentalProof"]["tmp_name"][$i], $target_file)) {
        echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
        // Update your SQL query to include the file path
        $sql = "INSERT INTO change_class_documentalproof (changeClassID, fileName) VALUES ('$generatedId', '$target_file')";
        $result = $conn->query($sql);
    } else {
      echo "Error uploading the file.";
    }
  }
  
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

$sql = "SELECT lecturerID, name FROM lecturer";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $lecturerID = $row['lecturerID'];
  $lecturerName = $row['name'];
  $lecturers[] = array("lecturerID" => $lecturerID, "val" => $lecturerName);
}

$query = "SELECT subject.subjectCode AS subjectCode, subject.name AS subjectName, subject_lecturer.lecturerID AS lecturerID FROM subject LEFT JOIN subject_lecturer ON subject.subjectCode=subject_lecturer.subjectCode";
$result = $conn->query($query);

while($row = $result->fetch_assoc()){
  $lecturerID = $row['lecturerID'];
  $subjectCode = $row['subjectCode'];
  $subjectName = $row['subjectName'];
  $subject = $subjectName . ' ' . $subjectCode;
  $subjects[$lecturerID][] = array("subjectCode" => $subjectCode, "val" => $subject);
}

$jsonLecturers = json_encode($lecturers);
$jsonSubjects = json_encode($subjects);

?>

<script type='text/javascript'>
  <?php
    echo "var lecturers = $jsonLecturers; \n";
    echo "var subjects = $jsonSubjects; \n";
  ?>

  function loadLecturers() {
      var select = document.getElementById("lecturersSelect");
      var userID = <?php echo json_encode($userid); ?>;

      select.onchange = updateSubjects;
      select.options[0] = new Option('Select the lecturer','');
      subjectsSelect.options[0] = new Option('Select the subject','');

      for (var i = 0; i < lecturers.length; i++) {
          var lecturer = lecturers[i];
          var option = new Option(lecturer.val, lecturer.lecturerID);
          select.options[i + 1] = option;

          // Check if the current lecturer's ID matches the user's ID
          if (lecturer.lecturerID === userID) {
              option.selected = true; // Set the option as selected
          }
      }
      
      updateSubjects.call(select);
  }

  function updateSubjects(){
    var lecturerID = this.value;
    var subjectsSelect = document.getElementById("subjectsSelect");
    subjectsSelect.options.length = 0; // Delete all options if any are present
    subjectsSelect.options[0] = new Option('Select the subject',''); 
    if (lecturerID != 'Select the lecturer'){
      for(var i = 0; i < subjects[lecturerID].length; i++){
        subjectsSelect.options[i] = new Option(subjects[lecturerID][i].val, subjects[lecturerID][i].subjectCode);
    }
    }  
  }

  function toggleInput() {
    var replacement = document.getElementById("replacement");
    var permanent = document.getElementById("permanent");

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

  </script>
  <script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewReplacement.php';
    }
  }
</script>

<style>
  th, td {
    padding-right: 50px;
    padding-bottom:5px;
    vertical-align: top;
  }
  table{
    margin:20px;
  }
</style>

  <body onload='loadLecturers()'>
  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Replacement/ Permanent Change of Class Room Venue/ Time Application Form</center></h3>
      <table>
      <tr>
        <th>Type Of Change:</th>
        <td>
          <input type="radio" name="typeOfChange" id="replacement" value="replacement" onclick="toggleInput()" checked>
          <label for="replacement">Class Replacement</label>
        </td>
        <td>
          <input type="radio" name="typeOfChange" id="permanent" value="permanent" onclick="toggleInput()">
          <label for="permanent">Permanent</label>
        </td>
      </tr>
      <tr>
        <th>Lecturer Name:</th>
        <td><select name='lecturerID' id='lecturersSelect' required></td>
      </tr>
      <tr>
        <th>Subject Code & Name:</th>
        <td><select name='subjectCode' id='subjectsSelect' required></td>
      </tr>
      </table>

      <table>
        <tr>
          <th></th>
          <th style="color:#061392;">Existing</th>
          <th style="color:#061392;">New</th>
        </tr>
        <tr>
          <th>Date:</th>
          <td><input type="date" id="existingDate" name="existingDate" required></td>
          <td><input type="date" id="newDate" name="newDate" required></td>
        </tr>
        <tr>
        <th>Day:</th>
          <td>
            <select name="existingDay" id="existingDay" disabled required>
              <option value="">Select day</option>
              <option value="Monday">Monday</option>
              <option value="Tuesday">Tuesday</option>
              <option value="Wednesday">Wednesday</option>
              <option value="Thursday">Thursday</option>
              <option value="Friday">Friday</option>
              <option value="Saturday">Saturday</option>
              <option value="Sunday">Sunday</option>
            </select>
          </td>
          <td>
            <select name="newDay" id="newDay" disabled required>
              <option value="">Select day</option>
              <option value="Monday">Monday</option>
              <option value="Tuesday">Tuesday</option>
              <option value="Wednesday">Wednesday</option>
              <option value="Thursday">Thursday</option>
              <option value="Friday">Friday</option>
              <option value="Saturday">Saturday</option>
              <option value="Sunday">Sunday</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Start Time:</th>
          <td><input type="time" id="existingTime" name="existingTime" value="09:00" step="1800" required></td>
          <td><input type="time" id="newTime" name="newTime" value="09:00" step="1800" required></td>
        </tr>
        <tr>
          <th>Hour:</th>
          <td colspan="2"><input type="number" id="hour" name="hour" min="1" value="2" required></td>
        </tr>
        <tr>
          <th>Venue:</th>
          <td><input type="text" id="existingVenue" name="existingVenue" placeholder="IEB 211" required></td>
          <td><input type="text" id="newVenue" name="newVenue" placeholder="IEB 211" required></td>
        </tr>
        <tr>
          <th>Reason:</th>
          <td colspan="2"><textarea placeholder="Leave your reason here" name="reason" id="reason" cols="60" required></textarea></td>
        </tr>
        <tr>
          <th>Documental Proof:</th>
          <td colspan="2"><input type="file" name="documentalProof[]" id="documentalProof" multiple required><br>
        <p style="color:grey;">**Hold down the Ctrl (windows) or Command (Mac) button to select multiple files**</p></td>
        </tr>
      </table>
      <table>
        <tr>
          <td style="padding-right:0px;"><input type="checkbox" name="agree" id="agree" required></td>
          <td><b>Personal Data Protection Act (PDPA)</b></td>
        </tr>
        <tr>
          <td colspan = "2">
          I understand and agree that Southern University College has the permission to use my personal data for the purpose of administering. I have read, understand and agreed to the Personal Data Protection Act of Southern University College. <br> (Note: You may access and update your personal data by writing to us at <a href="mailto:reg@sc.edu.my">reg@sc.edu.my</a>)
          </td>
        </tr>
      </table>
      <button name="apply" type="submit" class="btn btn-info" style="margin-left:20px; margin-right:20px; float:right;">Apply</button>
      <button name="apply" type="button" class="btn btn-outline-secondary" style="margin-bottom:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
    </form>
  </div>
  </body>
</html>
