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


  $category = $_POST['category'];
    if ($category == 'deferment') {
        $type = 1;
    } elseif ($category == 'withdrawal') {
        $type = 0;
    }
  $resons=$_POST['reasons'];
  $applicationDate=date("Y-m-d"); 

  $sql = "INSERT INTO deferment_record (defermentID, applicantID, applicantSignature, applicationDate, category, reasons) VALUES ('$generatedId', '$userid', '1', '$applicationDate' , '$type', '$resons')";
  $result=$conn->query($sql);

  if ($result === TRUE) {
    echo '<script type="text/javascript">';
    echo 'alert("Your application successfully submitted!");'; 
    echo 'window.location = "viewDeferment.php";';
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
      location.href = 'viewDeferment.php';
    }
  }
</script>


<div style="margin: 40px;">
  <form  action="" method="post" enctype="multipart/form-data">
    <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Deferment/ Withdrawal Application Form</center></h3>
    <div class="row" style="margin: 20px;">
      <label for="type" class="form-label" style="margin-top: 3px;">Category: </label>
        <div class="form-check form-check-inline" style="margin-left: 30px;">
          <input class="form-check-input" type="radio" name="category" id="deferment" value="deferment" onclick="toggleInput()" checked>
          <label class="form-check-label" for="inlineRadio1">Deferment</label>
        </div>
        <div class="form-check form-check-inline" style="margin-left: 10px;">
          <input class="form-check-input" type="radio" name="category" id="withdrawal" value="withdrawal" onclick="toggleInput()">
          <label class="form-check-label" for="inlineRadio2">Withdrawal</label>
        </div>
    </div>
    <div class="row" style="margin: 20px;">
      <label for="reason" id="reason"class="form-label" style="margin-top: 5px; margin-right: 30px;">Reason(s):</label>
        <textarea class="form-control" placeholder="Leave your reason here" name="reasons" id="reasons" required></textarea>
    </div>
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