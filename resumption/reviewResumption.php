<?php
include '../config.php';
include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
</script>

<div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px;"><center>Subject Registration Application</center></h3>
      <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; font-size: 110%"><b>Add Subject</b></label> 
          <button type="button" id="addRow" class="btn btn-secondary">Add Row</button>
      </div>
      <div class="row" style="margin: 20px;">
          <label for="id" class="form-label">Type:</label>
          <div class="form-check form-check-inline" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
            <label class="form-check-label" for="inlineRadio1">Add</label>
          </div>
          <div class="form-check form-check-inline" style="margin-left: 10px;">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
            <label class="form-check-label" for="inlineRadio2">Drop</label>
          </div>
      </div>
      <button name="Submit" type="submit" class="btn btn-info">Submit</button>
    </form>
  </div>
</div>

  </body>
</html>