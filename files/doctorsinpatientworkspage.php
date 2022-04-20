<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $_SESSION['outpatient_nurse_com'] = 'no';

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
    <div id="select_ward" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl">
                <tr>
                   <td style="text-align:right">
                        Select Your working Department
                   </td>
                   <td style="width:60%">
                       <select id="working_department" style="width:100%">
                        <option selected='selected'></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                   </td>
                </tr>
                <tr id="select_ward">
                    <td style="text-align:right">
                        Select Your working Ward
                    </td>
                    <td>
                    <div id="options_list">
                        <select  name='Ward_ID' style='width: 100%;height:30%'  id="Ward_ID" onclick='clearFocus(this)' required='required'>
                            <option selected='selected'> Select Your working Department First </option>
                        </select>
                    </div>
                    </td>
                </tr>
                <td colspan="2" align="right">
                    <input type="button" onclick="post_ward_id()" class="art-button-green" value="Open"/>
                </td>
        </tr> 
    </table>
</div>
<script>
    function post_ward_id(){
       var Ward_ID=$("#Ward_ID").val();
       var working_department=$("#working_department").val();
       if(Ward_ID==''||Ward_ID==null){
          alert("select ward first") 
          exit 
       }
       if(working_department==''||working_department==null){
          alert("select your working department first") 
          exit 
       }
       document.location="inpatientdoctorspage_select_ward.php?Ward_ID="+Ward_ID+'&finance_department_id='+working_department;
    }
    function select_ward_dialog(){
          $("#select_ward").dialog({
                        title: 'SELECT YOUR WARKING DEPARTMENT AND WARD',
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
    }
</script>
    
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center" ><b>IPD WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){ ?>
                    <a href='#' onclick="select_ward_dialog()">
                        <button style='width: 100%; height: 100%'>
                            Doctor's Works Page
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Works Page
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	    <?php if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){ ?>
                    <?php
			$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
			$permit=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
			$row=mysqli_fetch_array($permit);
			$Employee_Type=$row['Employee_Type'];
			if($Employee_Type == 'Doctor' ){ ?>
			<tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
                                <a href='doctorsindivinpatientperform.php'>
				    <button style='width: 100%; height: 100%'>
					 My Round Performance Report
				    </button>
				</a>
			    </td>
			</tr>	
			<?php } ?>
			<?php }else{ ?>
			 <tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<button style='width: 100%; height: 100%' onclick="return access_Denied();">
				    My Round Performance Report
				</button>
			    </td>
			</tr>
			<?php } ?>
                        
    <?php if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') { ?>
    <?php if ($Employee_Type == 'Doctor') { ?>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='surgery_performance_report.php?loc=doctorinp'>
                        <button style='width: 100%; height: 100%'>
                            My Surgery Performance Report
                        </button>
                    </a>
                </td>
            </tr>
 <?php }
 
// Surgery_Appointments.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage
 
    } ?>
            
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') { ?>
                        <?php if ($Employee_Type == 'Doctor') { ?>
                                <tr>
                                    <td style='text-align: center; height: 40px; width: 33%;'>
                                        <a href='Surgery_Appointments.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage&Inpatient=yes'>
                                            <button style='width: 100%; height: 100%'>
                                                Surgery Appointment List
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                    <?php }

                     } ?>
            
            
            
            
	    
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <?php
			$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
			$permit=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
			$row=mysqli_fetch_array($permit);
			$Employee_Type=$row['Employee_Type'];
			if($Employee_Type == 'Doctor' ){ ?>
			    <tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
				    <a href='transferdoctor.php?Section=DocInpatient&TransferDoctor=TransferDoctorThisPage'>
					<button style='width: 100%; height: 100%'>
					     Patient Transfer 
					</button>
				    </a>
				</td>
			    </tr>
                            
                           <tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
				    <a href='searchpatientinward.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>
					<button style='width: 100%; height: 100%'>
					     Work Station
					</button>
				    </a>
				</td>
			    </tr>
                            
                            
			<?php } ?>
                    <?php }else{ ?>
			<tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
				    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
					Patient Transfer
				    </button>
			        </td>
			</tr>
                        
                        
                        
                    <?php } ?>
                
        </table>
        </center>
</fieldset><br/>
<script>
    $(document).ready(function (e){
        $("#working_department").select2();
        $("#Ward_ID").select2();
    });
</script>
<script>
    $(document).ready(function() {
        $("#working_department").change(function(){
            var depertment_id=$('#working_department option:selected').val();

            $.ajax({
                type:'POST',
                url:'ajax_get_wards.php',
                data:{depertment_id:depertment_id},
                success:function(data){
                    $("#options_list").html(data); 
                    $("#Ward_ID").select2();
                }
                // error:function{alert('error')}
            });
        });
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
    include("./includes/footer.php");
?>