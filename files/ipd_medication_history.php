<?php
    include("./includes/header.php");
    include("./button_configuration.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
		    header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}


?>
<a href="pharmacyworks.php" class="art-button-green">BACK</a>
<style>
    .hover_active_cls:hover{
        background: #DEDEDE;
        cursor: pointer;
        color: #0088CC;
        font-weight: bold;
    }
    .hover_active_cls:active{
        background: #CCCCCC;
        cursor: pointer;
        color: #0088CC;
        font-size: 14px;
        font-weight: bold; 
    }
</style>
<fieldset>
    <legend align='center'><b>INPATIENT PATIENT LIST</b></legend>
    <div class="box box-primary">
        <div class="box-header">
            <div class="col-md-4"><h4>INPATIENT PATIENT LIST</h4></div>
            <div class="col-md-8">
                <table class="">
                    <tr>
                        <td><input type="text" placeholder="Enter Patient Name" onkeyup="filter_inpatient_list_for_medication_hist()" id='Patient_Name' style='text-align:center'/></td>
                        <td><input type="text" placeholder="Enter Patient Number"onkeyup="filter_inpatient_list_for_medication_hist()"  id='Registration_ID' style='text-align:center'/></td>                        
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body" style="height: 450px;overflow: scroll;overflow-x: hidden" id="inpatient_list">
            <table width ="100%">';
                <thead>
                     <tr >
                        <th style='width:5%;'>SN</th>
                        <th><b>PATIENT NAME</b></th>
                        <th><b>PATIENT NO</b></th>
                        <th><b>GENDER</b></th>
                        <th><b>AGE</b></th>
                        <th><b>SPONSOR</b></th>
                        <th><b>NEXT OF KIN</b></th>
                        <th><b>NEXT OF KIN NO</b></th>
                        <th><b>WARD</b></th>
                     </tr>
                </thead>
            </table>
        </div>
    </div>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    function filter_inpatient_list_for_medication_hist(){
        var Patient_Name=$("#Patient_Name").val();
        var Registration_ID=$("#Registration_ID").val();
       
        document.getElementById('inpatient_list').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_filter_inpatient_list_for_medication_hist.php',
            data:{Registration_ID:Registration_ID,Patient_Name:Patient_Name},
            success:function(data){
                $("#inpatient_list").html(data);
            }
        });
    }
    $(document).ready(function(){
        filter_inpatient_list_for_medication_hist()
    });
</script>
