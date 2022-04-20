<?php
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['from']) &&  $_SESSION['from']=="ebill"){
    unset($_SESSION['from']);
}
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href="laboratory_setup.php?LaboratorySetup=LaboratorySetupThisPage" class="art-button-green">BACK</a>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #CCCCCC;
        font-weight:bold;
    }
</style>

<fieldset>
<legend align=center><b>TAT Configuration</b></legend>
<div class="col-sm-12">
    <div class="box box-primary" style="height: 400px;overflow: auto">
        <div class="box-header">
        </div>
        <div class="box-body" >
            <div id="all_item_Name_list_body">
                <table class="table">
                    <thead>
                        <th style="width: 5%">S/N</th>
                        <th>Item Name</th>
                        <th>TAT Range</th>
                        <th>Value</th>
                        <th>Units</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="item_list_body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 </fieldset>
<div id="selected_amount_div"></div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script>

$.ajax({
    url: "ajax_tatconfiguration.php",
    method:"POST",
    data:{getFilterValue:''},
    dataType:"text",
    success:function(data){
        $('#item_list_body').html(data);
    }
});

function  update_tat(obj) {
    var output = obj.name.split("-");
    var min_range = document.getElementById(output[0]);
    var min_range_units = document.getElementById(output[1]);
    var Item_ID = obj.id;

    if (min_range_units.value === "") {
      alert("Please Select Units");
    }else{
      $.ajax({
          url: "ajax_tatconfiguration.php",
          method:"POST",
          data:{request:"",Item_ID:Item_ID,min_range:(min_range.value+min_range_units.value)},
          dataType:"text",
          success:function(data){
            if(data === "Saved Succsesfull."){
              $.ajax({
                  url: "ajax_tatconfiguration.php",
                  method:"POST",
                  data:{getFilterValue:''},
                  dataType:"text",
                  success:function(data){
                      $('#item_list_body').html(data);
                  }
              });
            }else{
              alert("Fail to Update");
            }
          }
      });
    }
}

$("#select_all_checkbox").click(function (e){
    $(".Item_ID").not(this).prop('checked', this.checked);

});

</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
