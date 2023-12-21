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

  $reason=$_POST['reason'];

  $target_dir = "uploads/paymentSlip/";
  $totalfiles = count($_FILES['paymentSlip']['name']);

  $sql = "INSERT INTO document_record (documentID, document, reason, applicantID, applicantSignature, applicationDate, counter) VALUES ('$generatedId', '$chk', '$reason', '$userid', '1', '$applicationDate', '1')";
  $result=$conn->query($sql);

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
    var documentText1 = document.getElementById("certOthers").value;
    document.getElementById("certOthersCheckbox").value = documentText1;
    var documentText2 = document.getElementById("others").value;
    document.getElementById("othersCheckbox").value = documentText2;
    var documentText3 = document.getElementById("year").value + document.getElementById("sem").value;
    document.getElementById("yearsemSelect").value = "Semester Academic Record " + documentText3;
    var documentText4 = document.getElementById("copies").value;
    document.getElementById("copiesCheckbox").value = "Syllabus " + documentText4 + " copies";
    var documentText5 = document.getElementById("number").value;
    var documentText6 = document.getElementById("subjects").value;
    document.getElementById("numberCheckbox").value = "Syllabus " + documentText5 + " subject  (Course Applied : " + documentText6 + ")";
  }

  function validateForm(){
    var isChecked = false;
    var checkboxes = [];

    for (var i = 1; i <= 17; i++) {
        var checkboxId = i;
        var checkboxElement = document.getElementById(checkboxId);
        checkboxes.push(checkboxElement);
    }

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            isChecked = true;
            break;
        }
    }

    if (!isChecked) {
        alert("Please select at least one subject!");
        return false;
    }

    return true;
  }

</script>

<script type='text/javascript'>
function toggleInput() {
    var checkbox10 = document.getElementById("10");
    var checkbox12 = document.getElementById("12");
    var checkbox14 = document.getElementById("14");
    var checkbox15 = document.getElementById("15");
    var checkbox17 = document.getElementById("17");

    if (checkbox10.checked) {
      others.disabled = false;
    } else{
      others.disabled = true;
    }
    if (checkbox12.checked) {
      year.disabled = false;
      sem.disabled = false;
    } else{
      year.disabled = true;
      sem.disabled = true;
    }
    if (checkbox14.checked) {
      copies.disabled = false;
    } else{
      copies.disabled = true;
    }
    if (checkbox15.checked) {
      number.disabled = false;
      subjects.disabled = false;
    } else{
      number.disabled = true;
      subjects.disabled = true;
    }
    if (checkbox17.checked) {
      certOthers.disabled = false;
    } else{
      certOthers.disabled = true;
    }
  }
</script>


  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Document Application Form</center></h3>
      <div class="row" style="margin: 10px; margin-left: 20px;"> 
        <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Documents</b></label> 
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="1" name="documents[]" value="Letter of KWSP" style="margin-right: 15px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of KWSP </b><1set> (@RM10)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="2" name="documents[]" value="Letter of MQA" style="margin-right: 15px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of MQA </b><1set> (@RM8)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 30px;"><b>Letter of certification </b><1set> (@RM5)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Type of Letter: </label>
        <input type="checkbox" id="3" name="documents[]" value="Letter of student status" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of student status</label>
        <input type="checkbox" id="4" name="documents[]" value="Letter of changing of programme" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of changing of programme</label>
        <input type="checkbox" id="5" name="documents[]" value="Letter of deferment / withdrawal" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of deferment / withdrawal</label>
        <input type="checkbox" id="6" name="documents[]" value="Letter of medium of instruction" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of medium of instruction</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="7" name="documents[]" value="Letter of completion of studies" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of completion of studies</label>
        <input type="checkbox" id="8" name="documents[]" value="Letter of certifying date of expected completion" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of certifying date of expected completion</label>
        <input type="checkbox" id="9" name="documents[]" value="Letter of postponement for PLKN / National Services" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Letter of postponement for PLKN / National Services</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="10" name="documents[]" value="" id="othersCheckbox" style="margin-right: 10px;" onclick="toggleInput()">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Others: </label>
        <input type="text" id="others" name="others" onkeyup="updateValue()" required disabled>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Academic Record:</b></label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="11" name="documents[]" value="Transcript" style="margin-right: 10px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;">Transcript (Student: RM20  Former Student: RM30)</label>
        <input type="checkbox" id="12" name="documents[]" value="" id="yearsemSelect" style="margin-right: 10px;" onclick="toggleInput()">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Semester Academic Record (@RM10)</label>
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Year</label>
        <select name="year" id="year" style="margin-right: 10px;" onchange="updateValue()" required disabled>
        <option value="">Select Year</option>
          <?php
            $currentYear = date("Y");
            for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
           ?>
        </select>
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Sem</label>
        <select name="sem" id="sem" onchange="updateValue()" required disabled> 
        <option value="">Select Sem</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <input type="checkbox" id="13" name="documents[]" value="Renew of Student ID Card" style="margin-right: 10px; margin-bottom: 12px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-bottom: 20px; margin-right: 30px; "><b>Renew of Student ID Card (@RM25)</b></label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Syllabus: </b></label>
        <input type="checkbox" id="14" name="documents[]" value="" id="copiesCheckbox" style="margin-right: 10px;" onclick="toggleInput()">
        <input type="text" id="copies" name="copies" onkeyup="updateValue()" required disabled>
        <p style="margin: 7px;  margin-top: 5px;">copies (@RM35)</p>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="15" name="documents[]" value="" id="numberCheckbox" style="margin-right: 10px;  margin-left: 60px;" onclick="toggleInput()">
        <input type="text" id="number" name="number" onkeyup="updateValue()" required disabled>
        <p style="margin: 7px; margin-top: 5px;">subject (@RM15)</p> 
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 10px;">Course/Subject Applied:</label>
        <input type="text" id="subjects" name="subjects" onkeyup="updateValue()" required disabled>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px; margin-top: 20px;">
        <input type="checkbox" id="16" name="documents[]" value="Student ID card for status verification at semester final examination" style="margin-right: 10px; margin-bottom: 12px;">
        <label for="document" class="form-label" style="margin-top: 5px; margin-bottom: 20px; margin-right: 30px;"><b>Student ID card for status verification at semester final examination </b>(@RM20)</label>
      </div>
      <div class="row" style="margin: 10px; margin-left: 20px;">
        <input type="checkbox" id="17" name="documents[]" value="" id="certOthersCheckbox" style="margin-right: 10px;" onclick="toggleInput()">
        <label for="document" class="form-label" style="margin-top: 5px; margin-right: 20px;"><b>Others: </b></label>
        <input type="text" id="certOthers" name="certOthers" onkeyup="updateValue()" required disabled>
      </div>
      <div class="row" style="margin: 20px;">
      <label for="reason" id="reason"class="form-label" style="margin-top: 5px; margin-right: 30px;">Reason(s):</label>
        <textarea class="form-control" placeholder="Leave your reason here" name="reason" id="reason" required></textarea>
      </div>
      <div class="row" style="margin: 20px; margin-top:30px; margin-bottom:0px;">
        <label for="paymentSlip" id="paymentSlip"class="form-label" style="margin-top: 5px; margin-right: 30px;">Payment Slip: </label>
        <input type="file" name="paymentSlip[]" id="paymentSlip" multiple required>
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
      <button name="apply" type="submit" class="btn btn-info" style="margin:20px; margin-top:0px; float:right;">Apply</button>
      <button name="apply" type="button" class="btn btn-outline-secondary" style="margin-bottom:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
    </form>
  </div>

  </body>
</html>
