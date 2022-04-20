<?php
include("./includes/connection.php");
include("./includes/header.php");
?>
<a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage' class='art-button-green'>
    BACK
 </a>
<?php

?>
<br>
<fieldset>
    <legend >SPONSOR PACKAGE SETTINGS</legend>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="">Select Package</label>
        </div>
        <select class="form-control" name="" id="package_id">
          <option value="">Select Package</option>
          <?php
            $package_list = mysqli_query($conn,"SELECT * FROM `tbl_nhif_scheme_package` ");
            while ($row = mysqli_fetch_object($package_list)) {
              echo "<option value='".$row->package_id."'>".$row->package_name."</option>";
            }
           ?>
        </select>
        <div class="form-group">
          <br>
          <input type="button" class='btn btn-primary btn-bg' style='width:100%;' name="" value="UPDATE" onclick="update_sponsor_package()">
        </div>
      </div>
      <div class="col-md-6">
        <div id="progress">

        </div>
      </div>
    </div>
</fieldset>

<script type="text/javascript">
  function update_sponsor_package(){
    var package_id = document.getElementById("package_id");
    // alert(package_id.value);

    $.ajax({
      url:"SPONSOR_API/NHIF/controller.php",
      type:"post",
      beforeSend:function(){
        $('#progress').text('Loading Items, Please wait...');
      },
      data:{service_type:'update_package', package_id:package_id.value},
      success:function(result){
        $('#progress').text(result);
      }
    });
  }
</script>
<?php
include("./includes/footer.php");
?>
