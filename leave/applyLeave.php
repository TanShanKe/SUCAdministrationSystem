<?php
include '../config.php';

session_start(); // Start the session

$allowedPositions = ["student"];
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
  $typeOfLeave = $_POST['typeOfLeave'];
    if ($typeOfLeave == 'incident') {
        $type = 1;
    } elseif ($typeOfLeave == 'funerary') {
        $type = 0;
    }
  $subjectCode=$_POST['subjectCode']; 
  $dateOfLeave=$_POST['dateOfLeave'];
  $noOfDays=$_POST['noOfDays'];
  $reason=$_POST['reason'];
  $applicationDate=date("Y-m-d"); 
  $uniqid = uniqid();

  $target_dir = "uploads/";
  //$target_file = $target_dir . basename($_FILES["documentalProof"]["name"]); 
  //$uploadOk = 1;
  // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $totalfiles = count($_FILES['documentalProof']['name']);

 /*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "pdf") {
    echo "Sorry, only JPG, JPEG, PNG, & PDF files are allowed.";
    $uploadOk = 0;
  }
  else if ($_FILES["documentalProof"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  } */
  // Check if the file has been uploaded
  //if(isset($_FILES["documentalProof"]) && $_FILES["documentalProof"]["error"] === UPLOAD_ERR_OK && $uploadOk == 1) {
      for($i=0;$i<$totalfiles;$i++){
      $file_name = uniqid() . "_" . basename($_FILES["documentalProof"]["name"][$i]);
      $target_file = $target_dir . $file_name;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["documentalProof"]["tmp_name"][$i], $target_file)) {
          echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
          // Update your SQL query to include the file path

          $sql = "INSERT INTO leave_documentalproof (leaveID, fileName) VALUES ('$generatedId', '$target_file')";
          $result = $conn->query($sql);
      } else {
        echo "Error uploading the file.";
      }
      }
          
      $sql = "INSERT INTO leave_record (leaveID, typeOfLeave, dateOfLeave, noOfDays, reason, applicantID, applicantSignature, applicationDate) VALUES ('$generatedId', '$type', '$dateOfLeave', '$noOfDays', '$reason', '$userid', '1', '$applicationDate')";
      $result = $conn->query($sql);

      foreach ($subjectCode as $selectedSubjectCode) {
        $lecturerID = $_POST['lecturerID'][$selectedSubjectCode];
        $sql = "INSERT INTO leave_subject (leaveID, subjectCode, lecturerID) VALUES ('$generatedId', '$selectedSubjectCode', '$lecturerID')";
          $result = $conn->query($sql);
      }

      if ($result === TRUE) {
          echo '<script type="text/javascript">';
          echo 'alert("Your application successfully submitted!");'; 
          echo 'window.location = "viewLeave.php";';
          echo '</script>';
      } else {
          echo "Error: " . $conn->error;
      }
          
/*} else {
  echo "File not uploaded or an error occurred.";
  
} */
}

//https://phppot.com/php/mysql-blob-using-php/#:~:text=Insert%20Image%20as%20MySQL%20BLOB,image%20file%20into%20a%20BLOB.

  //https://stackoverflow.com/questions/59760177/fetch-data-from-database-into-checkbox-and-get-the-selected-values-php

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";                        
?>

  <script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewLeave.php';
    }
  }
</script>

<style>
.subject {
  padding: 10px;
    border-style: solid;
    border-color: grey;
}
</style>


  <body>
  <div style="margin: 40px;">
  <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Student Incident & Funerary Leave Application Form</center></h3>
    <form  action="" method="post" enctype="multipart/form-data">
      <div class="row" style="margin: 20px;">
        <label for="type" class="form-label" style="margin-top: 3px;">Type Of Leave: </label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="typeOfLeave" id="incident" value="replacement" onclick="toggleInput()" checked>
            <label class="form-check-label" for="inlineRadio1">Incident Leave</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="typeOfLeave" id="funerary" value="permanent" onclick="toggleInput()">
            <label class="form-check-label" for="inlineRadio2">Funerary Leave</label>
          </div>
      </div>
    <div class="row" style="margin: 20px;">
        <label for="dateOfLeave" id="dateOfLeave"class="form-label" style="margin-top: 5px; margin-right: 30px;">Date of Leave: </label>
        <input type="date" id="dateOfLeave" name="dateOfLeave" required>
    </div>
    <div class="row" style="margin: 20px;">
        <label for="noOfDays" id="noOfDays"class="form-label" style="margin-top: 5px; margin-right: 30px;">No of Days: </label>
        <input type="number" id="noOfDays" name="noOfDays" required>
    </div>
    <div class="row" style="margin: 20px;">
    <label for="reason" id="reason"class="form-label" style="margin-top: 5px; margin-right: 30px;">Reason(s):</label>
      <textarea class="form-control" placeholder="Leave your reason here" name="reason" id="reason" required></textarea>
    </div>
    <div class="row" style="margin: 20px;">
        <label for="documentalProof" id="documentalProof"class="form-label" style="margin-top: 5px; margin-right: 30px;">Documental Proof: </label>
        <input type="file" name="documentalProof[]" id="documentalProof" required multiple>
    </div>
    <div class="row" style="margin: 20px;">
        <p style="color:grey;">**Hold down the Ctrl (windows) or Command (Mac) button to select multiple files**</p>
    </div>
    <div class="row" style="margin: 20px;">
    <label for="subject" id="subject"class="form-label" style="margin-top: 5px; margin-right: 30px;">Subject: </label>
        <table style="border-spacing: 10px;">
    <?php
    $sql = "select subject.name as subjectName, lecturer.name as lecturerName, subject_student.subjectCode, subject_student.lecturerID from subject_student left join subject on subject_student.subjectCode=subject.subjectCode left join lecturer on subject_student.lecturerID=lecturer.lecturerID where studentID = '$userid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $subjectCode=$row['subjectCode'];
        $lecturerID=$row['lecturerID']

       
        ?>
        
        <tr>
            <td class="subject">
            <input type="checkbox" name="subjectCode[]" value="<?php echo $subjectCode ?>">
            <input type="hidden" name="lecturerID[<?php echo $subjectCode ?>]" value="<?php echo $lecturerID ?>">
            </td>
            <td class="subject"><label for="subjectCode"><?php echo $row["subjectName"]; ?></label></td>
            <td class="subject"><label for="lecturerName"><?php echo $row["lecturerName"]; ?></label></td>
        </tr>
        <?php
      }
    }
    ?>
    </table>
</div><br>
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
  </body>
</html>
