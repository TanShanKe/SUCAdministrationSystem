<?php
include '../config.php';

session_start();

//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["afo", "aaro"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

  $documentID = $_GET['documentID'];

  $status = $_GET['status'];


$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
$sql1 = "SELECT document_record.documentID, applicantID as studentID, student.name AS studentName, student.contactNo AS contactNo, student.batchNo AS batchNo, student.icPassport AS icPassport, reason, document, applicationDate FROM document_record LEFT JOIN student ON document_record.applicantID = student.studentID WHERE document_record.documentID = '$documentID'";

$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $documentID=$row['documentID'];
    $studentName=$row['studentName'];
    $studentID=$row['studentID'];
    $contactNo=$row['contactNo'];
    $batchNo=$row['batchNo'];
    $icPassport=$row['icPassport'];
    $document=$row['document'];
    $reason=$row['reason'];
    $applicationDate=$row['applicationDate'];
  }
}           

  // Submit the review data
  if(isset($_POST['submit'])){

    $documentID = $_POST['documentID'];
    $status = $_POST['status'];
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $signatureDate=date("Y-m-d"); 

    $sql1 = "SELECT counter FROM document_record WHERE documentID = '$documentID'";

    $result1 = $conn->query($sql1);
  
    if ($result1->num_rows > 0) {
      while ($row = $result1->fetch_assoc()) {
        $counter=$row['counter'];
      }
    }  
    $counters = $counter+1;

    if($position == 'afo'){
    $decision = $_POST['decision'];
    if ($decision == 'approved') {
        $ackResult = 1;
    } elseif ($decision == 'disapproved') {
        $ackResult = 0;
    }

    if(($status == 'review' || $status == 'update') && $ackResult == 0){
      $comment = $_POST['comment'];

      $sql = "INSERT INTO document_review (documentID, afoDecision, afoComment, afoSignature, afoId, afoDate, counter) VALUES ('$documentID','$ackResult','$comment','1','$userid','$signatureDate','$counters')";
      $result=$conn->query($sql);

      $sql2 = "UPDATE document_record SET counter = '$counters', applicationStatus = '0', waitingStatus = '0' WHERE documentID = '$documentID'";
      $result2=$conn->query($sql2);

      if ($result === TRUE) {
        echo '<script type="text/javascript">';
        echo 'alert("Your application successfully submitted!");'; 
        echo 'window.location = "viewDocumentApplied.php";';
        echo '</script>';
      } else {
          echo "Error: " . $conn->error;
      }

    }elseif (($status == 'review' || $status == 'update') && $ackResult == 1) {
      $target_dir = "uploads/receipt/";
      $target_file = $target_dir . basename($_FILES["officialReceipt"]["name"]); 
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
        echo "Sorry, only JPG, JPEG, PNG, & PDF files are allowed.";
        $uploadOk = 0;
      }
      else if ($_FILES["officialReceipt"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }
      // Check if the file has been uploaded
      else if(isset($_FILES["officialReceipt"]) && $_FILES["officialReceipt"]["error"] === UPLOAD_ERR_OK && $uploadOk == 1) {
      $file_name = uniqid() . "_" . basename($_FILES["officialReceipt"]["name"]);
      $target_file = $target_dir . $file_name;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["officialReceipt"]["tmp_name"], $target_file)) {
          echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
          // Update your SQL query to include the file path

          $sql = "INSERT INTO document_review (documentID, afoDecision, afoSignature, afoID, afoDate, counter) VALUES ('$documentID','$ackResult','1','$userid','$signatureDate','$counters')";
          $result=$conn->query($sql);

          $sql2 = "UPDATE document_record SET applicationStatus = '1', waitingStatus = '0', officialReceipt = '$target_file' WHERE documentID = '$documentID'";
          $result2=$conn->query($sql2);

          if ($result === TRUE) {
              echo '<script type="text/javascript">';
              echo 'alert("Successfully submitted!");'; 
              echo 'window.location = "viewDocumentApplied.php";';
              echo '</script>';
          } else {
              echo "Error: " . $conn->error;
          }
          
      } else {
          echo "Error uploading the file.";
      }
    }
    else {
        echo "File not uploaded or an error occurred.";
    } 
    }
   } if($position == 'aaro'){
    if($status == 'Collection'){
      $collectionDate = $_POST['collectionDate'];

      $sql = "UPDATE document_record
      SET collectionDate = '$collectionDate', aaroCollectionDate = '$signatureDate', aaroCollectionID = '$userid', collectionStatus = '0'
      WHERE documentID = '$documentID'";
      $result=$conn->query($sql);

      if ($result === TRUE) {
        echo '<script type="text/javascript">';
        echo 'alert("Your application successfully submitted!");'; 
        echo 'window.location = "viewDocumentApplied.php";';
        echo '</script>';
      } else {
          echo "Error: " . $conn->error;
      }

    } if($status == 'Collecting'){
      $sql = "UPDATE document_record
      SET aaroCollectedDate = '$signatureDate', aaroCollectedID = '$userid', collectionStatus = '1'
      WHERE documentID = '$documentID'";
      $result=$conn->query($sql);

      if ($result === TRUE) {
        echo '<script type="text/javascript">';
        echo 'alert("Your application successfully submitted!");'; 
        echo 'window.location = "viewDocumentApplied.php";';
        echo '</script>';
      } else {
          echo "Error: " . $conn->error;
      }

    }

   }


  }

include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<style>
.thResult {
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
.thReview, .tdReview {
    padding-right: 50px;
    padding-bottom:5px;
    vertical-align: top;
    border-style: none;
}
table,td{
  border-style: solid;
  border-color: black;
}
</style>

<script type='text/javascript'>

function toggleInput() {
  var approved = document.getElementById("approved");
  var disapproved = document.getElementById("disapproved");

  if (disapproved.checked) {
    officialReceipt.disabled = true;
    comment.disabled = false;
  } else if(approved.checked){
    comment.disabled = true;
    officialReceipt.disabled = false;
  }
}
</script>

<script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewDocumentApplied.php';
    }
  }
  function back() {
    location.href = 'viewDocumentApplied.php';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Document Application</h3>
  </div>
    <div class="row" style="margin:20px; margin-top:15px">
    <label for="" class="form-label" >Application Details</label>
    <table class="table">  
        <tr>
          <th class="thInfo">ID</th><td class="table-light " colspan="3"><?php echo $documentID; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Name</th><td class="table-light"><?php echo $studentName; ?></td>
          <th class="thInfo">Contact No.</th><td class="table-light"><?php echo $contactNo; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">Student ID</th><td class="table-light"><?php echo $studentID; ?></td>
          <th class="thInfo">Batch No.</th><td class="table-light"><?php echo $batchNo; ?></td>
        </tr> 
        <tr>
          <th class="thInfo">IC / Passport</th><td class="table-light " colspan="3"><?php echo $icPassport; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Reasons</th><td class="table-light " colspan="3"><?php echo $reason; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Documents</th><td class="table-light" colspan="3"><?php echo $document; ?></td>
        </tr>
        <tr>
          <th class="thInfo">Payment Slip</th><td class="table-light" colspan="3">
        <?php
        $sql = "SELECT documentID, fileName FROM document_paymentslip WHERE documentID = '$documentID' AND counter = 0";
        $result = $conn->query($sql);
        $no =1;
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $fileName=$row['fileName']; ?>
            <a href="<?php echo $fileName; ?>" target="_blank"><?php echo $no."."; ?>Click here to view the payment slip</a> <br>
           <?php
            $no++;
          }
        }
        ?>
        </td>
        </tr>
        <tr>
          <th class="thInfo">Application Date</th><td class="table-light" colspan="3"><?php echo $applicationDate; ?></td>
        </tr>
    </table>

        <?php

    $sql3 = "SELECT counter FROM document_record WHERE documentID = '$documentID'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
      while ($row = $result3->fetch_assoc()) {
        $counter=$row['counter']; 
          if($counter > 1){
            echo '<label for="" class="form-label" >Account and Finance Office (Disapproved)</label>'; 
          }
    }}

    $sql = "SELECT administrator.name as name, afoDate, afoComment, counter FROM document_review LEFT JOIN administrator ON document_review.afoID=administrator.administratorID WHERE documentID = '$documentID' AND afoDecision = 0 ORDER BY counter ASC";
    $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $name=$row['name'];
          $date=$row['afoDate'];
          $comment=$row['afoComment'];
          $counter=$row['counter'];

          ?>
          <table class="table">  
          <tr>
            <th class="thResult">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thResult">Date</th><td class="table-light"><?php echo $date; ?></td>
          </tr> 
          <tr>
            <th class="thResult">Comment</th><td class="table-light" colspan="3"><?php echo $comment; ?></td>
          </tr>

          <?php
    $sql1 = "SELECT documentID, fileName, counter FROM document_paymentslip WHERE documentID = '$documentID' ORDER BY counter ASC";
    $result1 = $conn->query($sql1);

    $paymentSlips = array();

    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $counter1 = $row['counter'];
            $fileName = $row['fileName'];
            // Initialize array for each counter if not exists
            if (!isset($paymentSlips[$counter1])) {
                $paymentSlips[$counter1] = array();
            }
            // Store payment slips in the array
            $paymentSlips[$counter1][] = $fileName;
        }
    }

    $sql2 = "SELECT documentID, counter, applicationDate FROM document_paymentslip WHERE documentID = '$documentID' GROUP BY counter ORDER BY counter ASC";
    $result2 = $conn->query($sql2);
    
    $applicationDate = array();  // Initialize $applicationDate array outside the loop
    
    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $counter2 = $row['counter'];
            $date = $row['applicationDate'];
    
            // Initialize array for each counter if not exists
            if (!isset($applicationDate[$counter2])) {
                $applicationDate[$counter2] = array();
            }
            $applicationDate[$counter2][] = $date;
        }
    }

    // Iterate over each counter and display the corresponding table
    foreach ($paymentSlips as $counter1 => $slips) {
      if($counter1 == $counter){
        $no = 1; ?>
        <tr>
            <th class="thInfo">Payment Slip</th>
            <td class="table-light" colspan="3">
                <?php
                foreach ($slips as $fileName) {
                    echo '<a href="' . $fileName . '" target="_blank">' . $no . '. Click here to view the payment slip</a> <br>';
                    $no++;
                }
                ?>
            </td>
        </tr>
        <tr>
            <th class="thInfo">Application Date</th>
            <td class="table-light" colspan="3">
              <?php
            foreach ($applicationDate as $counter2 => $dates) {
              if($counter2 == $counter1){
                foreach ($dates as $date) {
                  echo $date;
              }
              }
            } ?>
            </td>
        </tr>
        <?php
      }
    }
    ?>
        </table> 
      <?php
        } 
      } ?>
    <?php 

    if($position == 'afo'){ ?>
    <?php 
    if($status == 'review' || $status == 'update'){ ?>
    <form  action="reviewDocument.php" method="post" enctype="multipart/form-data">
    <table style="margin-bottom:20px; border-style: none;">
      <tr>
        <th class="thReview">Decision:</th>
        <td class="tdReview">
          <input type="radio" name="decision" id="approved" value="approved" onclick="toggleInput()" checked>
          <label for="approved">Approved</label>
        </td>
        <td class="tdReview">
          <input type="radio" name="decision" id="disapproved" value="disapproved" onclick="toggleInput()">
          <label for="disapproved">Disapproved</label>
        </td>
      </tr>
      <tr>
        <th class="thReview">Official Receipt:</th>
        <td class="tdReview">
          <input type="file" name="officialReceipt" id="officialReceipt" required>
        </td>
      </tr>
      <tr>
          <th class="thReview">Comment:</th>
          <td colspan="2" class="tdReview"><textarea placeholder="Leave your comment here" name="comment" id="comment" cols="60" disabled required></textarea></td>
        </tr>
    </table>
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        <input type="hidden" name="documentID" value="<?php echo $documentID; ?>">
        <input type="hidden" name="status" value="<?php echo $status; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px; float:right;">Submit</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()">Cancel</button>
      </form>
    <?php }
      }
      
      if($status == 'completed' || $status == 'Collection' || $status == 'Collecting' || $status == 'Collected'){ 
        
        echo '<label for="" class="form-label" >Account and Finance Office (Approved)</label>'; 

        $sql = "SELECT administrator.name as name, afoDate, officialReceipt FROM document_review LEFT JOIN administrator ON document_review.afoID=administrator.administratorID LEFT JOIN document_record ON document_review.documentID=document_record.documentID WHERE document_review.documentID = '$documentID' AND afoDecision = 1 ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $name=$row['name'];
            $date=$row['afoDate'];
            $officialReceipt=$row['officialReceipt'];
          }

        ?>

        <table class="table">  
        <tr>
          <th class="thResult">Name</th><td class="table-light"><?php echo $name; ?></td>
          <th class="thResult">Date</th><td class="table-light"><?php echo $date; ?></td>
        </tr> 
        <tr>
          <th class="thResult">Official Receipt</th><td class="table-light" colspan="3"><a href="<?php echo $officialReceipt; ?>" target="_blank">Click here to view the official receipt</a></td>
        </tr>
        </table> 
      <?php } 
      } 
      
      if($position =='aaro'){
        if($status == 'Collection'){ ?>
          <form  action="reviewDocument.php" method="post" enctype="multipart/form-data">
          <table style="margin-bottom:20px; margin-top:20px; border-style: none;">
          <th class="tdReview">Collection Date:</th>
          <td class="tdReview"><input type="date" name="collectionDate" id="collectionDate" required></td>
        </table>
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        <input type="hidden" name="documentID" value="<?php echo $documentID; ?>">
        <input type="hidden" name="status" value="<?php echo $status; ?>">
        <button name="submit" type="submit" class="btn btn-primary float:right;" style="margin-left:20px; float:right;">Submit</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
      </form>
        <?php
        }
        if($status == 'Collecting'){

         echo '<label for="" class="form-label" >Account and Finance Office</label>'; 
              
            $sql = "SELECT administrator.name as name, aaroCollectionDate, collectionDate FROM document_record LEFT JOIN administrator ON document_record.aaroCollectionID=administrator.administratorID WHERE documentID = '$documentID'";
            $result = $conn->query($sql);
  
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $name=$row['name'];
                $aaroCollectionDate=$row['aaroCollectionDate'];
                $collectionDate=$row['collectionDate'];
              }
            ?>
  
            <table class="table">  
            <tr>
              <th class="thResult">Name</th><td class="table-light"><?php echo $name; ?></td>
              <th class="thResult">Date</th><td class="table-light"><?php echo $aaroCollectionDate; ?></td>
            </tr> 
            <tr>
              <th class="thResult">Collection Date</th><td class="table-light" colspan="3"><?php echo $collectionDate; ?></td>
            </tr>
            </table> 
            <?php
            }  ?>

          <form  action="reviewDocument.php" method="post" enctype="multipart/form-data">
          <table style="margin-bottom:20px; margin-top:20px; border-style: none;">
          <th class="tdReview">Collection Acknowledge:</th>
          <td class="tdReview"><input type="checkbox" name="collected" id="collected" style="margin-top: 7px; margin-right: 20px;" required></td>
        </table>
        <table style="border:none;">
          <tr>
            <td style="vertical-align: top; border:none;"><input type="checkbox" name="agree" id="agree" style="margin-top: 7px; margin-right: 20px;" required></td>
            <td style="border:none;"><label for="pdpa"><strong>Decision Responsibility Acknowledgment</strong></label></td>
          </tr>
        </table>
        <p>I voluntarily acknowledge and accept full responsibility for the decision I am about to make, understanding that my choice will have significant consequences.</p>
        <input type="hidden" name="documentID" value="<?php echo $documentID; ?>">
        <input type="hidden" name="status" value="<?php echo $status; ?>">
        <button name="submit" type="submit" class="btn btn-primary float:right;" style="margin-left:20px; float:right;">Submit</button>
        <button name="cancel" type="button" class="btn btn-outline-secondary" style="margin-left:20px; float:right;" onclick="confirmCancel()";>Cancel</button>
    </form> <?php
        }

        if($status == 'Collected'){

          echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office  (Collection Date)</label>'; 
               
             $sql = "SELECT administrator.name as name, aaroCollectionDate, collectionDate FROM document_record LEFT JOIN administrator ON document_record.aaroCollectionID=administrator.administratorID WHERE documentID = '$documentID'";
             $result = $conn->query($sql);
   
             if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                 $name=$row['name'];
                 $aaroCollectionDate=$row['aaroCollectionDate'];
                 $collectionDate=$row['collectionDate'];
               }
             ?>
   
             <table class="table">  
             <tr>
               <th class="thResult">Name</th><td class="table-light"><?php echo $name; ?></td>
               <th class="thResult">Date</th><td class="table-light"><?php echo $aaroCollectionDate; ?></td>
             </tr> 
             <tr>
               <th class="thResult">Collection Date</th><td class="table-light" colspan="3"><?php echo $collectionDate; ?></td>
             </tr>
             </table> 
             <?php
             }  

             echo '<label for="" class="form-label" >Academic Affairs, Admission & Registration Office  (Collected Date)</label>'; 
               
             $sql = "SELECT administrator.name as name, aaroCollectedDate FROM document_record LEFT JOIN administrator ON document_record.aaroCollectionID=administrator.administratorID WHERE documentID = '$documentID'";
             $result = $conn->query($sql);
   
             if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                 $name=$row['name'];
                 $aaroCollectedDate=$row['aaroCollectedDate'];
               }
             ?>
   
             <table class="table">  
             <tr>
               <th class="thResult">Name</th><td class="table-light"><?php echo $name; ?></td>
               <th class="thResult">Date</th><td class="table-light"><?php echo $aaroCollectedDate; ?></td>
             </tr> 
             <tr>
               <th class="thResult">Collection Status</th><td class="table-light" colspan="3">Collected</td>
             </tr>
             </table> 
             <?php
             }  ?>
 
           <?php
         }
       
      } ?>
    </div>
    <?php if($status=='waiting update' || $status=='completed' || $status=='Collected'){ ?>
      <button name="back" type="button" class="btn btn-outline-secondary" style = "margin-bottom:20px; margin-right:20px; float: right;" onclick="back()";>Back</button>
        <?php } ?>
  </div>
  </body>
</html>