<?php
include '../config.php';

session_start();


//Must login to access this page
if (!isset($_SESSION['userid'])) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
}

//Only dean or hod & aaro & afo can access this page
$allowedPositions = ["student"];
if (!isset($_SESSION['userid']) || !in_array($_SESSION['position'], $allowedPositions)) {
  header("Location: http://localhost/sucadministrationsystem/index.php");
  exit();
}

//Get info from previous page
$documentID = $_GET['documentID'];
$status = $_GET['status'];
$userid = $_SESSION['userid'];
$position = $_SESSION['position'];

//Get the applicant info
$sql1 = "SELECT document_record.documentID, applicantID as studentID, student.name AS studentName, student.contactNo AS contactNo, student.batchNo AS batchNo, student.icPassport AS icPassport, reason, document, applicationDate, updateApplicationDate, afoSignature FROM document_record LEFT JOIN student ON document_record.applicantID = student.studentID WHERE document_record.documentID = '$documentID'";

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
    $updateApplicationDate=$row['updateApplicationDate'];
    $afoSignature=$row['afoSignature'];
  }
}      


if(isset($_POST['submit'])){
  $documentID = $_POST['documentID'];

  date_default_timezone_set('Asia/Kuala_Lumpur');
  $date=date("Y-m-d"); 

  $target_dir = "uploads/updatePaymentSlip/";
  $totalfiles = count($_FILES['updatePaymentSlip']['name']);

  for($i=0;$i<$totalfiles;$i++){    
  $file_name = uniqid() . "_" . basename($_FILES["updatePaymentSlip"]["name"][$i]);
  $target_file = $target_dir . $file_name;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["updatePaymentSlip"]["tmp_name"][$i], $target_file)) {
          echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
          // Update your SQL query to include the file path

          $sql = "INSERT INTO document_updatepaymentslip (documentID, fileName) VALUES ('$documentID', '$target_file')";
          $result=$conn->query($sql);
      
        } else {
        echo "Error uploading the file.";
        }

  }

  $sql = "UPDATE document_record
      SET updateApplicationDate = '$date', updateApplicantSignature = '1'
      WHERE documentID = '$documentID'";
      $result=$conn->query($sql);

  if ($result === TRUE) {
    echo '<script type="text/javascript">';
    echo 'alert("Successfully submitted!");'; 
    echo 'window.location = "viewDocument.php";';
    echo '</script>';
  } else {
      echo "Error: " . $conn->error;
  }
}


include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

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

<script>
  var baseUrl = '../';
  function confirmCancel() {
    if (confirm('Are you sure you want to leave?')) {
      location.href = 'viewDocument.php';
    }
  }
  function back() {
    location.href = 'viewDocument.php';
  }
</script>

<div class="container-fluid" style="width: 95%;" >
  <div class="d-flex justify-content-center" style=" margin-top:40px ">
  <h3 style="margin-right: 20px">Document Application</h3>
  </div>
    <div style="margin:40px; margin-top:15px">
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
        $sql = "SELECT documentID, fileName FROM document_paymentslip WHERE documentID = '$documentID'";
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
        if($status=='Update'){
          echo '<label for="" class="form-label" >Account and Finance Office</label>'; 

          $sql = "SELECT administrator.name as name, updateAfoSignatureDate, comment FROM document_record LEFT JOIN administrator ON document_record.updateAfoID=administrator.administratorID WHERE documentID = '$documentID'";
          $result = $conn->query($sql);
  
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $name=$row['name'];
              $updateAfoSignatureDate=$row['updateAfoSignatureDate'];
              $comment=$row['comment'];
            }   
          ?>
          <table class="table">  
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">Date</th><td class="table-light"><?php echo $updateAfoSignatureDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light" colspan="3"><?php echo $comment; ?></td>
          </tr>
          </table> 
        <?php } ?>

        <form  action="viewDocumentResult.php" method="post" enctype="multipart/form-data">
        <br>
        <label for="updatePaymentSlip" class="form-label" style="margin-top: 5px; margin-right: 30px;">Payment Slip: </label>
        <input type="file" name="updatePaymentSlip[]" id="updatePaymentSlip" multiple>
        <table style="border:none; margin-top:20px; margin-bottom:30px;">
        <tr>
          <td style="padding-right:0px; border:none;"><input type="checkbox" name="agree" id="agree" required></td>
          <td style="border:none;"><b>Personal Data Protection Act (PDPA)</b></td>
        </tr>
        <tr>
          <td style="border:none;" colspan = "2">
          I understand and agree that Southern University College has the permission to use my personal data for the purpose of administering. I have read, understand and agreed to the Personal Data Protection Act of Southern University College. <br> (Note: You may access and update your personal data by writing to us at <a href="mailto:reg@sc.edu.my">reg@sc.edu.my</a>)
          </td>
        </tr>
        </table>
        <input type="hidden" name="documentID" value="<?php echo $documentID; ?>">
        <button name="submit" type="submit" class="btn btn-primary" style="margin-left:20px;">Submit</button>
        <button name="cancel" type="button" class="btn btn-secondary" style="margin-left:20px;" onclick="confirmCancel()";>Cancel</button>
        </form>

        <?php
        } 

        if($status=='Updating' || ($status=='Completed' && $updateApplicationDate!=null) || ($status=='Collection' && $updateApplicationDate!=null) || ($status=='Collected' && $updateApplicationDate!=null)){ 
          echo '<label for="" class="form-label" >Account and Finance Office</label>'; 

          $sql = "SELECT administrator.name as name, updateAfoSignatureDate, comment FROM document_record LEFT JOIN administrator ON document_record.updateAfoID=administrator.administratorID WHERE documentID = '$documentID'";
          $result = $conn->query($sql);
  
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $name=$row['name'];
              $updateAfoSignatureDate=$row['updateAfoSignatureDate'];
              $comment=$row['comment'];
            }   
          ?>
          <table class="table">  
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">Date</th><td class="table-light"><?php echo $updateAfoSignatureDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Comment</th><td class="table-light" colspan="3"><?php echo $comment; ?></td>
          </tr>
          </table> 
        <?php } ?>
        <label for="" class="form-label" >Updated Application Details</label>
            <table class="table">
            <tr>
              <th class="thInfo">Payment Slip</th><td class="table-light" colspan="3">
            <?php
            $sql = "SELECT documentID, fileName FROM document_updatepaymentslip WHERE documentID = '$documentID'";
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
              <th class="thInfo">Application Date</th><td class="table-light" colspan="3"><?php echo $updateApplicationDate; ?></td>
            </tr>
           </table>
           <?php
        }
        
        elseif($status=='Completed' ||$status == 'Collection' || $status == 'Collected'){
          echo '<label for="" class="form-label" >Account and Finance Office</label>'; 

          $sql = "SELECT administrator.name as name, afoSignatureDate, officialReceipt FROM document_record LEFT JOIN administrator ON document_record.afoID=administrator.administratorID WHERE documentID = '$documentID'";
          $result = $conn->query($sql);
  
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $name=$row['name'];
              $afoSignatureDate=$row['afoSignatureDate'];
              $officialReceipt=$row['officialReceipt'];
            }
  
          ?>
  
          <table class="table">  
          <tr>
            <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
            <th class="thReview">Date</th><td class="table-light"><?php echo $afoSignatureDate; ?></td>
          </tr> 
          <tr>
            <th class="thReview">Official Receipt</th><td class="table-light" colspan="3"><a href="<?php echo $officialReceipt; ?>" target="_blank">Click here to view the official receipt</a></td>
          </tr>
          </table> 
        <?php } } 

        if($status =='Collection'){
          echo '<label for="" class="form-label" >AARO (Collection Date)</label>'; 
               
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
               <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
               <th class="thReview">Date</th><td class="table-light"><?php echo $aaroCollectionDate; ?></td>
             </tr> 
             <tr>
               <th class="thReview">Collection Date</th><td class="table-light" colspan="3"><?php echo $collectionDate; ?></td>
             </tr>
             </table> 
             <?php
             }  
             
        } if($status == 'Collected'){

          echo '<label for="" class="form-label" >AARO (Collection Date)</label>'; 
               
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
               <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
               <th class="thReview">Date</th><td class="table-light"><?php echo $aaroCollectionDate; ?></td>
             </tr> 
             <tr>
               <th class="thReview">Collection Date</th><td class="table-light" colspan="3"><?php echo $collectionDate; ?></td>
             </tr>
             </table> 
             <?php
             }  

             echo '<label for="" class="form-label" >AARO (Collected Date)</label>'; 
               
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
               <th class="thReview">Name</th><td class="table-light"><?php echo $name; ?></td>
               <th class="thReview">Date</th><td class="table-light"><?php echo $aaroCollectedDate; ?></td>
             </tr> 
             <tr>
               <th class="thReview">Collection Status</th><td class="table-light" colspan="3">Collected</td>
             </tr>
             </table> 
             <?php
             }  ?>
 
           <?php
         }



        
        if($status!='Update'){ ?>
          <button name="back" type="button" class="btn btn-secondary" style = "margin-top:20px;" onclick="back()";>Back</button>
        <?php } ?>

    </div>
  </div>
  </body>
</html>