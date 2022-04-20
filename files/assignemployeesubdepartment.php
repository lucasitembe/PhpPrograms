<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }

    $HRWork = "";
    if(isset($_GET['HRWork']) && $_GET['HRWork'] == "true") {
        $HRWork = $_GET['HRWork'];
    } else {
        $HRWork = "";
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' ){ 

            if($HRWork == "true") {
                ?>
                    <a href='listofemployeetoassigntosubdepartment.php?ListOfEmployeesToAssignSubDepartment=ListOfEmployeesToAssignSubDepartmentThisPage&HRWork=<?= $HRWork; ?>' class='art-button-green'>
                        BACK
                    </a>
                <?php
            } else { ?>
                    <a href='listofemployeetoassigntosubdepartment.php?ListOfEmployeesToAssignSubDepartment=ListOfEmployeesToAssignSubDepartmentThisPage' class='art-button-green'>
                        BACK
                    </a>
            <?php
            }
?>
    <!-- <a href='listofemployeetoassigntosubdepartment.php?ListOfEmployeesToAssignSubDepartment=ListOfEmployeesToAssignSubDepartmentThisPage' class='art-button-green'>
        BACK
    </a> -->
<?php  } 
    if(isset($_SESSION['userinfo']['Appointment_Works']) && $_SESSION['userinfo']['Setup_And_Configuration'] !='yes'){
        echo "<a href='listofemployeetoassigntosubdepartment.php?ListOfEmployeesToAssignSubDepartment=ListOfEmployeesToAssignSubDepartmentThisPage&HRWork=true' class='art-button-green'>BACK</a>";
    }
} ?>

<!-- get sub department -->
<script type="text/javascript" language="javascript">
    function getSubDepartment(Department_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetSubDepartment.php?Department_ID='+Department_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('list_of_subdepartment').innerHTML = data;	
    }
</script>


 <?php
    $Select_Assigned_Branches = mysqli_query($conn,"select * from tbl_employee where Employee_ID = '$Employee_ID'");
    $no=mysqli_num_rows($Select_Assigned_Branches);
    if($no>0){
	while($row = mysqli_fetch_array($Select_Assigned_Branches)){
	    $Employee_Name = $row['Employee_Name'];
	    $Employee_Title = $row['Employee_Title'];
	}
    }else{
	$Employee_Name = 'Unknown Employee Name';
	$Employee_Title = 'Unknown Employee Title';
    }
?>
<center>
    <br/>
    <fieldset style="height:550px">
            <legend align="center" ><b>EMPLOYEE ASSIGN SUB DEPARTMENT</b></legend>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <table class="table">
                    <tr>
                        <td>
                            <b>EMPLOYEE PARTICULARS</b> 
                        </td> 
                    </tr>
                    <tr>
                                    <td><b>Employee Name</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Name; ?>'></td>
                                
                                    <td><b>Employee Title</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Title; ?>'></td>
                                </tr>
                           
                           
                    
                </table>
            </div>
        </div>
    </div>
            <div class="row">
                <div class="col-md-12"><label id="feedback_message"></label></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            Select Department
                             <?php if($Employee_Name == 'Unknown Employee Name' || $Employee_ID == 0){ ?>
                            <select name='Department_Name' id='Department_Name' disabled='disabled' onchange='getSubDepartment(this.value)' required='required'>
                        <?php }else{ ?>
                            <select name='Department_Name' id='Department_Name' onchange='getSubDepartment(this.value)' required='required'>
                        <?php } ?>
                            <option selected='selected'></option>
                            <?php
                                $data = mysqli_query($conn,"select Department_ID, Department_Name from tbl_department where Department_Status = 'active'");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['Department_ID'].'>'.$row['Department_Name'].'</option>';
                                }
                            ?>    
                        </select>
                        </div>
                        <div class="box-body" id="list_of_subdepartment" style="height:300px;overflow-y: scroll"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h6>LIST OF ASSIGNED SUB DEPARTMENT</h6>
                        </div>
                        <div class="box-body" style="height:295px;overflow-y: scroll" id="list_of_employee_assigned_sub_department">
                            <?php require "Assigned_Sub_Department_iframe.php"; ?>
                            <!--<iframe width='100%' src='Assigned_Sub_Department_iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&AssignedClinics=AssignedClinicsThisPage'></iframe>-->
                        </div>
                    </div>
                </div>
            </div>
    </fieldset>

	
 
 



 <?php
    include("./includes/footer.php");
?>
<script>
function add_subdepartment(Sub_Department_ID,Department_ID){
    var Employee_ID='<?= $Employee_ID ?>';
     $("#feedback_message").html("Adding Sub Department.Please Wait...");
    $.ajax({
        type:'POST',
        url:'ajax_add_subdepartment.php',
        data:{Sub_Department_ID:Sub_Department_ID,Department_ID:Department_ID,Employee_ID:Employee_ID},
        success:function(data){
            $("#feedback_message").html(data);
            $("#feedback_message").css("background","white");
            $("#feedback_message").css("color","green");
            $("#feedback_message").css("font-weight","bold");
            $("#feedback_message").css("font-size","20px");
            display_employee_assigned_subdepartement();
        },
    });
}
function display_employee_assigned_subdepartement(){
    var Employee_ID='<?= $Employee_ID ?>';
    $.ajax({
        type:'POST',
        url:'Assigned_Sub_Department_iframe.php',
        data:{Employee_ID:Employee_ID},
        success:function(data){
            $("#list_of_employee_assigned_sub_department").html(data);;
        },
    }); 
}
</script>