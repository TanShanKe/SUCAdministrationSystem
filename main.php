<?php
include 'config.php';
include 'header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '';
</script>

<style>
    .font {
        font-family: "Verdana, Arial", cursive;
        font-size:18px;
		color:#320618;
    }
    .image {
        width: 100px;
        height: 100px;
    }
</style>

<div class="container" style="padding-top: 50px;">
    <center>
  <div class="row" >
    <div class="col-sm" style="margin: 20px;">
        <a href="subjectRegistration/applySubjectRegistration.php">
            <img src="images/subjects.png" class="image">
            <label class="form-label font">Subject Registration Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="subjectRegistration/viewSubjectRegistration.php">
            <img src="images/leave.png" class="image"><br>
            <label class="form-label font">Incident & Funerary Leave Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="subjectRegistration/viewSubjectRegistration.php">
            <img src="images/change.png" class="image"><br>
            <label class="form-label font">Replacement/ Permanent Change of Class Room Venue/ Time</label>
        </a>
    </div>
    </div>
    <div class="row">
    <div class="col-sm" style="margin: 20px;">
        <a href="subjectRegistration/viewSubjectRegistration.php">
            <img src="images/deferment.png" class="image"><br>
            <label class="form-label font">Deferment/ Withdrawal Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="resumption/viewResumption.php">
            <img src="images/resumption.png" class="image"><br>
            <label class="form-label font">Resumption of Studies Application</label>
        </a>
    </div>
    <div class="col-sm" style="margin: 20px;">
        <a href="subjectRegistration/viewSubjectRegistration.php">
            <img src="images/document.png" class="image"><br>
            <label class="form-label font">Document Application</label>
        </a>
    </div>
    </div>
</center>
</div>

</body>
</html>