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

  $yearOfDeferment=$_POST['yearOfDeferment'];
  $semOfDeferment=$_POST['semOfDeferment']; 
  $yearOfResumption=$_POST['yearOfResumption']; 
  $semOfResumption=$_POST['semOfResumption'];
  $applicationDate=date("Y-m-d"); 

  $sql = "INSERT INTO resumption_of_studies_record (yearOfDeferment, semOfDeferment, yearOfResumption, semOfResumption, resumptionID, applicantID, applicantSignature, applicationDate) VALUES ('$yearOfDeferment', '$semOfDeferment', '$yearOfResumption', '$semOfResumption', '$generatedId', '$userid', '1', '$applicationDate')";
  $result=$conn->query($sql);

  if ($result === TRUE) {
    echo '<script type="text/javascript">';
    echo 'alert("Your application successfully submitted!");'; 
    echo 'window.location = "viewResumption.php";';
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
      location.href = 'viewResumption.php';
    }
  }
</script>

<div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Resumption of Studies Application Form</center></h3>
      <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Deferment Details</b></label> 
      </div>
      <div class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Year of Deferment</label>
        <select name="yearOfDeferment" id="yearOfDeferment" style="margin-right: 50px;">
            <?php
            $currentYear = date("Y");
            for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select>
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Sem of Deferment</label>
        <select name="semOfDeferment" id="semOfDeferment">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        </select>
      </div>
      <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Resumption Details</b></label> 
      </div>
      <div class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Year of Resumption</label>
        <select name="yearOfResumption" id="yearOfResumption" style="margin-right: 50px;">
            <?php
            $currentYear = date("Y");
            for ($i = $currentYear; $i <= $currentYear + 2; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select>
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Sem of Resumption</label>
        <select name="semOfResumption" id="semOfResumption">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
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
