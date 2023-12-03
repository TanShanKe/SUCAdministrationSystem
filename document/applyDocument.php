<?php
include '../config.php';

session_start(); // Start the session

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
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

  $checkbox1=$_POST['documents'];  
  $chk="";  
  foreach($checkbox1 as $chk1)  
   {  
      $chk .= $chk1.",";  
   }  
  $applicationDate=date("Y-m-d"); 

  $target_dir = "uploads/paymentSlip/";
  $totalfiles = count($_FILES['paymentSlip']['name']);

  for($i=0;$i<$totalfiles;$i++){    
  $file_name = uniqid() . "_" . basename($_FILES["paymentSlip"]["name"][$i]);
  $target_file = $target_dir . $file_name;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["paymentSlip"]["tmp_name"][$i], $target_file)) {
          echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
          // Update your SQL query to include the file path

          $sql = "INSERT INTO document_paymentslip (documentID, fileName) VALUES ('$generatedId', '$target_file')";
          $result=$conn->query($sql);
      
        } else {
        echo "Error uploading the file.";
        }

  }

        $sql = "INSERT INTO document_record (documentID, document, applicantID, applicantSignature, applicationDate) VALUES ('$generatedId', '$chk', '$userid', '1', '$applicationDate')";
        $result=$conn->query($sql);

        if ($result === TRUE) {
          echo '<script type="text/javascript">';
          echo 'alert("Your application successfully submitted!");'; 
          echo 'window.location = "viewDocument.php";';
          echo '</script>';
        } else {
            echo "Error: " . $conn->error;
        }

  }


include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewDocument.php';
    }
  }

  function updateValue() {
    var documentText = document.getElementById("certOthers").value;
    document.getElementById("certOthersCheckbox").value = documentText;
    var documentText = document.getElementById("others").value;
    document.getElementById("othersCheckbox").value = documentText;
    var documentText = document.getElementById("year").value + document.getElementById("sem").value;
    document.getElementById("yearsemSelect").value = "Semester Academic Record " + documentText;
  }
</script>

<div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Document Application Form</center></h3>
      <div class="row" style="margin: 10px; margin-left: 20px;"> 
        <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Documents</b></label> 
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="Letter of KWSP" style="margin-right: 15px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of KWSP </b><1set> (@RM10)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="Letter of MQA" style="margin-right: 15px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of MQA </b><1set> (@RM8)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of certification </b><1set> (@RM5)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Type of Letter: </label>
        <input type="checkbox" name="documents[]" value="Letter of student status" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of student status</label>
        <input type="checkbox" name="documents[]" value="Letter of changing of programme" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of changing of programme</label>
        <input type="checkbox" name="documents[]" value="Letter of deferment / withdrawal" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of deferment / withdrawal</label>
        <input type="checkbox" name="documents[]" value="Letter of medium of instruction" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of medium of instruction</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="Letter of completion of studies" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of completion of studies</label>
        <input type="checkbox" name="documents[]" value="Letter of certifying date of expected completion" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of certifying date of expected completion</label>
        <input type="checkbox" name="documents[]" value="Letter of postponement for PLKN / National Services" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of postponement for PLKN / National Services</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="" id="othersCheckbox" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Others: </label>
        <input type="text" id="others" name="others" onkeyup="updateValue()">
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Academic Record:</b></label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="Transcript" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Transcript (Student: RM20  Former Student: RM30)</label>
        <input type="checkbox" name="documents[]" value="" id="yearsemSelect" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Semester Academic Record (@RM10)</label>
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Year</label>
        <select name="year" id="year" style="margin-right: 10px;" onchange="updateValue()">
        <option value="">Select Year</option>
          <?php
            $currentYear = date("Y");
            for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
           ?>
        </select>
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Sem</label>
        <select name="sem" id="sem" onchange="updateValue()">
        <option value="">Select Sem</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <input type="checkbox" name="documents[]" value="Renew of Student ID Card" style="margin-right: 10px; margin-bottom: 12px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-bottom: 20px; margin-right: 30px; "><b>Renew of Student ID Card (@RM25)</b></label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Syllabus: </b></label>
        <input type="checkbox" name="documents[]" value="" style="margin-right: 10px;">
        <input type="text" id="document" name="document">
        <p style="margin: 7px;  margin-top: 5px;">copies (@RM35)</p>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="" style="margin-right: 10px;  margin-left: 60px;">
        <input type="text" id="document" name="document">
        <p style="margin: 7px; margin-top: 5px;">subject (@RM15)</p> 
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Course/Subject Applied:</label>
        <input type="text" id="document" name="document">
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <input type="checkbox" name="documents[]" value="Student ID card for status verification at semester final examination" style="margin-right: 10px; margin-bottom: 12px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-bottom: 20px; margin-right: 30px;"><b>Student ID card for status verification at semester final examination </b>(@RM20)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" name="documents[]" value="" id="certOthersCheckbox" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Others: </b></label>
        <input type="text" id="certOthers" name="certOthers" onkeyup="updateCheckboxValue()">
      </div>
      <div class="row" style="margin: 20px; margin-top:30px; margin-bottom:0px;">
        <label for="paymentSlip" id="paymentSlip"class="form-label" style="margin-top: 5px; margin-right: 30px;">Payment Slip: </label>
        <input type="file" name="paymentSlip[]" id="paymentSlip" multiple>
      </div>
      <div class="row" style="margin-left: 20px; margin-top:0px">
        <p style="color:grey;">**Hold down the Ctrl (windows) or Command (Mac) button to select multiple files**</p>
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
