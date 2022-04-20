<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['dhis2_dataset_name'])){
  $dhis2_dataset_name=$_GET['dhis2_dataset_name'];
}else{
  $dhis2_dataset_name="";
}
if(isset($_GET['dataset_id'])){
   $dataset_id=$_GET['dataset_id'];
}else{
    $dataset_id="";
}
if(isset($_GET['dhis2_auto_dataset_id'])){
    $dhis2_auto_dataset_id=$_GET['dhis2_auto_dataset_id'];
}else{
    $dhis2_auto_dataset_id="";
}


?>
<a href="dhis2_add_data_elements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class='art-button-green'>ADD DATA ELEMENT</a>
<a href="data_element_values_source.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class='art-button-green'>DATA ELEMENT VALUES SOURCE SETUP</a>
<a href='dhis2_api.php' class='art-button-green'>
        BACK
</a>
<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
<fieldset>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b><?= $dhis2_dataset_name ?></b></legend>
    <div class="col-md-12">
        <table class="pull-right">
            <tr>
                <td>Organization Unit</td>
                <td>
                    <?php
                    $organization_unit_id=0;
                    $sql_select_org_unit_result=mysqli_query($conn,"SELECT orgUnit,organization_unit_id FROM tbl_organization_unit") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_org_unit_result)>0){
                            
                          $rows=mysqli_fetch_assoc($sql_select_org_unit_result);
                          $organization_unit_id=$rows['organization_unit_id'];
                          $orgUnit=$rows['orgUnit'];
                          
                        }
                    ?>
                    <input type="text" id="orgUnit" class="form-control" onkeyup="update_organization_unit(<?= $organization_unit_id ?>)" placeholder="Enter Organization Unit" value="<?= $orgUnit ?>"/></td>
                <td>Complete Date</td>
                <td><input type="text" readonly="readonly" id="complete_date" style="background: #FFFFFF"placeholder="Enter Complete Date" class="form-control date"/></td>
                <td>Period</td>
                <td><input type="text" class="form-control" id="period_year" style="background: #FFFFFF" placeholder="Enter Period year"/></td>
                <td>
                    <select class="form-control" id="period_type">
                        <option value="">Select Period type</option>
                        <option value="P1D">Daily</option>
                        <option value="P7D">Weekly</option>
                        <option value="01">Monthly-1</option>
                        <option value="02">Monthly-2</option>
                        <option value="03">Monthly-3</option>
                        <option value="04">Monthly-4</option>
                        <option value="05">Monthly-5</option>
                        <option value="06">Monthly-6</option>
                        <option value="07">Monthly-7</option>
                        <option value="08">Monthly-8</option>
                        <option value="09">Monthly-9</option>
                        <option value="10">Monthly-10</option>
                        <option value="11">Monthly-11</option>
                        <option value="12">Monthly-12</option>
                        <option value="Q1">Quarterly</option>
                        <option value="S1">Six-monthly</option>
                        <option value="AprilS1">Six-month April</option>
                        <option value="">Yearly</option>
                        <option value="Oct">Financial October</option>
                        <option value="April">Financial April</option>
                        <option value="July">Financial July</option>
                    </select>
                </td>
                <td>
                    <input type="button" class="art-button-green" onclick="pull_data_element_values_automatically()" value="PULL DATA ELEMENTS VALUE"/>
                </td>
            </tr>
        </table>
     </div>
    <div  class="col-md-12"style='margin-top:15px;height: 370px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF'>
        <table class="table table-condensed">
            <tr>
                <td colspan="5">
                    <hr/>
                </td>
            </tr>
            <tr>
                <td width="5%"><b>S/No.</b></td>
                <td><b>Id</b></td>
                <td><b>Display Name</b></td>
                <td><b>Data Element Value</b></td>
                <td style='text-align:right'><b>Confirm</b></td>
            </tr>
            <tr>
                <td colspan="5">
                    <hr/>
                </td>
            </tr>
            <tbody id="data_element_n_values_area">
            <?php 
               /* $sql_select_data_elements_result=mysqli_query($conn,"SELECT dataelement_value,dhis2_dataelement_auto_id,dhis2_dataelement_id,categoryOptionCombo,displayname FROM tbl_dhis2_dataelements WHERE dataset_id='$dataset_id'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_data_elements_result)>0){
                    $count++;
                    while($data_element_rows=mysqli_fetch_assoc($sql_select_data_elements_result)){
                        $dhis2_dataelement_id=$data_element_rows['dhis2_dataelement_id'];
                        $categoryOptionCombo=$data_element_rows['categoryOptionCombo'];
                        $displayname=$data_element_rows['displayname'];
                        $dhis2_dataelement_auto_id=$data_element_rows['dhis2_dataelement_auto_id'];
                        $dataelement_value=$data_element_rows['dataelement_value'];
                        echo "<tr>
                                    <td>$count.</td>
                                    <td>$dhis2_dataelement_id</td>
                                    <td>$displayname</td>
                                    <td><input type='text' class='form-control' id='data_element_value$dhis2_dataelement_auto_id' onkeyup='save_data_element_value($dhis2_dataelement_auto_id)' placeholder='DateElement Value' value='$dataelement_value'/>
                                        <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' id='ajax_loder$dhis2_dataelement_auto_id'width='20' height='20'>
                                    </td>
                             </tr>";
                        $count++;
                    }
                }*/
            ?>
            </tbody>
        </table>
    </div>
</fieldset>
<fieldset>
    <div class="col-md-2"></div><div class="col-md-3"><input type="text" placeholder="Enter Username" id="username" class="form-control"/></div><div class="col-md-3"><input type="password" class="form-control" id="password" placeholder="Enter Password"/></div><div id="send_to_dhis2_serverprogressive_div" class="pull-right"></div><input type='button' value='SEND TO DHIS2 SERVER' onclick="verify_user_privileges()" class="art-button-green pull-right"/>
</fieldset>
<div id="dhis2_feedback"></div>
<div id="confirm_dataelement_value_dialog" style="background: #CCCCDD;display: none">
    <div id='confirm_data_element_value_content_area' style="background: #FFFFFF;height: 95%;width: 100%;margin-bottom: 4px"> </div>
    <input type="button" value="CONFIRMED" class="art-button-green pull-right" onclick="close_this_dialog('confirm_dataelement_value_dialog')"/>
</div>
<script>
    function close_this_dialog(confirm_dataelement_value_dialog){
       $("#"+confirm_dataelement_value_dialog).dialog('close'); 
    }
    function save_data_element_value(dhis2_dataelement_auto_id){
      $("#ajax_loder"+dhis2_dataelement_auto_id).show();  
      var data_element_value=$("#data_element_value"+dhis2_dataelement_auto_id).val();
      $.ajax({
          type:'GET',
          url:'save_dhis2_data_element_value.php',
          data:{data_element_value:data_element_value,dhis2_dataelement_auto_id:dhis2_dataelement_auto_id},
          success:function(data){
             $("#ajax_loder"+dhis2_dataelement_auto_id).hide();  
             console.log(data)
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function verify_user_privileges(){
        var username=$("#username").val();   
        var password=$("#password").val();  
        var validate=0;
        if(password==""){
           $("#password").css("border","2px solid red");
           validate++;
        }else{
            $("#password").css("border","");
        }
        if(username==""){
           $("#username").css("border","2px solid red");
           validate++;
        }else{
            $("#username").css("border","");
        } 
        if(validate<=0){
            $.ajax({
                type:'POST',
                url:'verify_user_privileges_for_sending_dhis2.php',
                data:{username:username,password:password},
                success:function(data){
                    console.log(data);
                    if(data=='access_granted'){
                        send_data_to_dhis2();
                    }else{
                        alert("Invalid Username or Password or you do not have enough privilage to send data to DHIS2 Server");
                    }
                }
            });
        }
    }
    function send_data_to_dhis2(){
        var period_year=$("#period_year").val();   
        var period_type=$("#period_type").val();   
        var complete_date=$("#complete_date").val();   
       
        var orgUnit=$("#orgUnit").val();   
        var dataset_id='<?= $dataset_id ?>';  
        document.getElementById('send_to_dhis2_serverprogressive_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'send_data_to_dhis2_server.php',
            data:{period_year:period_year,period_type:period_type,complete_date:complete_date,dataset_id:dataset_id,orgUnit:orgUnit},
            success:function(data){
               // console.log(data);
                $("#dhis2_feedback").html(data);
                $("#send_to_dhis2_serverprogressive_div").html("");
                $("#dhis2_feedback").dialog({
                        title: 'DHIS2 FEEDBACK MESSAGE',
                        width: '60%',
                        height: 350,
                        modal: true,
                    });
            }
        });
    }
    function update_organization_unit(organization_unit_id){
        var orgUnit=$("#orgUnit").val();
        $.ajax({
            type:'GET',
            url:'update_organization_unit.php',
            data:{organization_unit_id:organization_unit_id,orgUnit:orgUnit},
            success:function (data){
                console.log(data);
            }
        });
    }
    function pull_data_element_values_automatically(){
        document.getElementById('data_element_n_values_area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
         var period_year=$("#period_year").val();   
        var period_type=$("#period_type").val();   
        var complete_date=$("#complete_date").val();   
        var orgUnit=$("#orgUnit").val();   
        var dataset_id='<?= $dataset_id ?>';   
        $.ajax({
            type:'POST',
            url:'pull_data_element_values_automatically.php',
            data:{period_year:period_year,period_type:period_type,complete_date:complete_date,dataset_id:dataset_id,orgUnit:orgUnit},
            success:function(data){
               $("#data_element_n_values_area").html(data);
            }
        });
    }
    function open_confirm_dialog(dhis2_dataelement_id,categoryOptionCombo,displayname,dataset_id){
            $.ajax({
            type:'POST',
            url:'confirm_dataelement_value.php',
            data:{dhis2_dataelement_id:dhis2_dataelement_id,categoryOptionCombo:categoryOptionCombo,displayname:displayname,dataset_id:dataset_id},
            success:function(data){
               // console.log(data);
                $("#confirm_data_element_value_content_area").html(data);
                $("#confirm_dataelement_value_dialog").dialog({
                        title: 'CONFIRM DATA ELEMENT VALUE DIALOG',
                        width: '70%',
                        height: 550,
                        modal: true,
                    });
            }
        });
    }
</script>
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
