<?php
include '../config.php';

session_start(); // Start the session

if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'student') {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

$userid = $_SESSION['userid'];

$yearOfDeferment=null;
$semOfDeferment=null;

$sql = "SELECT YEAR(applicationDate) AS yearOfDeferment, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) BETWEEN 10 AND 12 THEN 3 WHEN MONTH(MAX(applicationDate)) BETWEEN 1 AND 2 THEN 4 ELSE 1 END AS semOfDeferment FROM deferment_record WHERE applicantID = '$userid' ORDER BY defermentID DESC LIMIT 1";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $yearOfDeferment=$row['yearOfDeferment'];
    $semOfDeferment=$row['semOfDeferment'];
  }
}

if(isset($_POST['apply'])){

  date_default_timezone_set('Asia/Kuala_Lumpur');

  $sql4 = "SELECT defermentID FROM deferment_record WHERE applicantID = '$userid' ORDER BY defermentID DESC LIMIT 1";

  $result4 = $conn->query($sql4);
  if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
      $defermentID=$row['defermentID'];
    }
  }

  $sql3 = "SELECT YEAR(applicationDate) AS yearOfDeferment, CASE WHEN MONTH(MAX(applicationDate)) BETWEEN 3 AND 5 THEN 1 WHEN MONTH(MAX(applicationDate)) BETWEEN 6 AND 9 THEN 2 WHEN MONTH(MAX(applicationDate)) BETWEEN 10 AND 12 THEN 3 WHEN MONTH(MAX(applicationDate)) BETWEEN 1 AND 2 THEN 4 ELSE 1 END AS semOfDeferment FROM deferment_record WHERE defermentID = '$defermentID'";

  $result3 = $conn->query($sql3);
  if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
      $yearOfDeferment=$row['yearOfDeferment'];
      $semOfDeferment=$row['semOfDeferment'];
    }
  }
  if($semOfDeferment == 4){
    $yearOfDeferment = $yearOfDeferment-1;
    $semOfDeferment = 3;
  }

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
  $yearOfResumption=$_POST['yearOfResumption']; 
  $semOfResumption=$_POST['semOfResumption'];
  $applicationDate=date("Y-m-d"); 

  $sql = "INSERT INTO resumption_of_studies_record (defermentID, yearOfDeferment, semOfDeferment, yearOfResumption, semOfResumption, resumptionID, applicantID, applicantSignature, applicationDate) VALUES ('$defermentID','$yearOfDeferment', '$semOfDeferment', '$yearOfResumption', '$semOfResumption', '$generatedId', '$userid', '1', '$applicationDate')";
  $result=$conn->query($sql);

  //INSERT INTO resumption_of_studies_record (yearOfDeferment, semOfDeferment, yearOfResumption, semOfResumption, resumptionID, applicantID, applicantSignature, applicationDate) VALUES ('2023', 'A', '2024', 'A', '2312001', 'B210157B', '1', '2023-12-12')

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

<?php 
if ($semOfDeferment == 4 || $semOfDeferment == 3) {
  $sem = array("1", "2", "3");
} elseif ($semOfDeferment == 1) {
  $sem = array("2", "3");
} elseif ($semOfDeferment == 2) {
  $sem = array("3");
}
if ($semOfDeferment == 4 ) {
  $yearOfDeferment = $yearOfDeferment-1;
}
$currentMonth=date("M");
?>

window.onload = function() {

    const d = new Date();
    let month = d.getMonth();
    let year = d.getFullYear();

    var years = {
        <?php if ($semOfDeferment == 3 || $semOfDeferment == 4): ?>
            "<?php echo $yearOfDeferment + 1; ?>": <?php echo json_encode($sem); ?>
        <?php else: ?>
            "<?php echo $yearOfDeferment ?>": <?php echo json_encode($sem); ?>,
        <?php endif; ?>
        <?php if ($semOfDeferment != 4 && $semOfDeferment != 3): ?>
            <?php if ($semOfDeferment == 1): ?>
                "<?php echo $yearOfDeferment + 1 ?>": ["1"]
            <?php elseif ($semOfDeferment == 2): ?>
                "<?php echo $yearOfDeferment + 1 ?>": ["1", "2"]
            <?php endif; ?>
        <?php endif; ?>
    };

    var yearSel = document.getElementById("yearOfResumption");
    var semSel = document.getElementById("semOfResumption");

    semSel.length = 0;
    var selectedYear = yearSel.value;

    // Check if the selected year is defined in the years object
    if (years[selectedYear]) {
        for (var i = 0; i < years[selectedYear].length; i++) {
          var optionValue = years[selectedYear][i];
            var optionText = optionValue;

            // Create a new Option element
            var option = new Option(optionText, optionValue);

            if(selectedYear==year){
              if(month>=3 && month<=5){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
            }
            if(month>=6 && month<=9){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
              if (optionValue == 2) {
                option.disabled = true;
              }
            }
            if(month>=10 && month<=12){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
              if (optionValue == 2) {
                option.disabled = true;
              }
              if (optionValue == 3) {
                option.disabled = true;
              }
            }
            }

            // Add the option to the select element
            semSel.options[semSel.options.length] = option;


        }
    }

    yearSel.onchange = function() {
    semSel.length = 0;
    var selectedYear = this.value;

    // Check if the selected year is defined in the years object
    if (years[selectedYear]) {
        // Display correct values
        for (var i = 0; i < years[selectedYear].length; i++) {
            var optionValue = years[selectedYear][i];
            var optionText = optionValue;

            // Create a new Option element
            var option = new Option(optionText, optionValue);

            if(selectedYear==year){
              if(month>=3 && month<=5){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
            }
            if(month>=6 && month<=9){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
              if (optionValue == 2) {
                option.disabled = true;
              }
            }
            if(month>=10 && month<=12){ 
              if (optionValue == 1) {
                option.disabled = true;
              }
              if (optionValue == 2) {
                option.disabled = true;
              }
              if (optionValue == 3) {
                option.disabled = true;
              }
            }
            }

            // Add the option to the select element
            semSel.options[semSel.options.length] = option;
        }
    }
}
}

</script>



  <div style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px; margin-bottom: 30px;"><center>Resumption of Studies Application Form</center></h3>
      <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; color:#061392;"><b>Resumption Details</b></label>
      </div>
      <div class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Year of Resumption</label>
        <select name="yearOfResumption" id="yearOfResumption" style="margin-right: 50px;" required>
            <?php
            $currentYear = date("Y");
            if ($semOfDeferment == 3 || $semOfDeferment == 4) {
              $yearOfDeferment = $yearOfDeferment+1;
              echo "<option value=\"$yearOfDeferment\"";
              if ($yearOfDeferment < $currentYear) {
                  echo "disabled";
              }
              echo ">$yearOfDeferment</option>";
          } else {
              for ($i = $yearOfDeferment; $i <= $yearOfDeferment + 1; $i++) {
                  echo "<option value=\"$i\"";
                  if ($i<$currentYear) {
                    echo "disabled=\"disabled\"";
                }
                  echo ">$i</option>";
              }
          }
            ?>
        </select>
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Sem of Resumption</label>
        <select name="semOfResumption" id="semOfResumption" required>
        </select>
        <p style="color:grey; margin-top:20px;">**If no option can be selected means you had deferment of studies more than 1 year and no resumption of studies application can be done**</p></td>
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
      <button name="apply" type="submit" class="btn btn-info" style="margin-left:20px; margin-right:20px; float:right;">Apply</button>
      <button name="apply" type="button" class="btn btn-outline-secondary" style="margin-bottom:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
    </form>
  </div>

  </body>
</html>
