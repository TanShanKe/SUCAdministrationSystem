<?php
include '../config.php';


include '../header.php';
echo "<body style='background-color:#E5F5F8'>";
?>

<script>
  var baseUrl = '../';
</script>

<style>
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

<div class="row">
  <div class="col" style="margin: 40px;">
    <form  action="" method="post" enctype="multipart/form-data">
      <h3 style="margin-top: 10px;"><center>Subject Registration Application</center></h3>
      <div class="row" style="margin: 20px;"> 
          <label class="form-label" style="margin-top: 10px; margin-right: 30px; font-size: 110%"><b>Add Subject</b></label> 
          <button type="button" id="addRow" class="btn btn-secondary">Add Row</button>
      </div>
      <div id="select-container" class="row" style="margin: 20px;">
        <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Subject</label>
        <select name="subject" class="form-control" style= "width: 90%"><br>
            <option value="">Select the subject</option>
            <?php
                $sql="select * from subject";
                $result=$conn->query($sql);
                if($result->num_rows>0){
                    while($row=$result->fetch_assoc()){
                      $subjectCode=$row['subjectCode'];
                      $name=$row['name'];
            ?>
            <option value="<?php echo $subjectCode;?>"><?php echo $subjectCode." ";?><?php echo $name;?></option>
            <?php
                    }
                }
            ?>
        </select>
        

        <script>
        $(document).ready(function() {
            const maxRows = 7;
            
            $("#addRow").click(function() {
                if ($(".select-row").length < maxRows) {
                  const newRow = `
                      <div class="select-row" style="margin: 20px;">
                          <label for="subject" class="form-label" style="margin-top: 5px; margin-right: 30px;">Subject</label>
                          <select name="subject" class="form-control" style="width: 90%;">
                              <option value="">Select the subject</option>
                              <?php
                                  $sql = "select * from subject";
                                  $result = $conn->query($sql);
                                  if ($result->num_rows > 0) {
                                      while ($row = $result->fetch_assoc()) {
                                          $subjectCode = $row['subjectCode'];
                                          $name = $row['name'];
                              ?>
                              <option value="<?php echo $subjectCode; ?>"><?php echo $subjectCode . " "; ?><?php echo $name; ?></option>
                              <?php
                                      }
                                  }
                              ?>
                          </select>
                      </div>
                  `;
                  $("#select-container").append(newRow);

                }
            });
          });
    </script>

    
<table class="table">  
        <tr>
          <th class="thInfo">Application ID</th><td class="table-light"><select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
            <option value="0">sem</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select></td>
          <th class="thInfo">Application ID</th><td class="table-light"><select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
            <option value="0">sem</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select></td>
        </tr> 
        <tr>
        <th class="thInfo">Application ID</th><td class="table-light"><select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
            <option value="0">sem</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select></td>
        <th class="thInfo">Application ID</th><td class="table-light"><select name="selected_sem" id="selected_sem" style="margin-right: 30px;">
            <option value="0">sem</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select></td>
      </tr> 
    </table>

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
      <button name="Apply" type="submit" class="btn btn-info">Apply</button>
    </form>
  </div>
</div>

  </body>
</html>