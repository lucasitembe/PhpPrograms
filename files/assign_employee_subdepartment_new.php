<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Editing_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Current_Username = $_SESSION['userinfo']['Given_Username'];

   if(isset($_SESSION['userinfo'])){
?>
    <a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } ?>

<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
    
    input[type="radio"] {
        -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
        -moz-appearance: checkbox;    /* Firefox */
        -ms-appearance: checkbox;     /* not currently supported */
    }
</style>

<fieldset>
    <legend align="center"> SETUP TO ASSIGN EMPLOYEE(S) WITH SUB-DEPARTIMENT(S)  </legend>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>LIST OF ALL EMPLOYEES</h4><br>
                </div>
                <input type='text' name='Employee_Name' id='Employee_Name' autocomplete="off"   onkeyup='Search_Employee()' class="form-control" placeholder='search by employee name' style="width:90%; text-align: center;">

               <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_employees'>Select All
                            </label>
                        </td>
                        <td>
                        <select name='Designation' id='Designation'  onchange="Search_Employee()">
                            <option selected disabled>Select Designation</option>
                            <?php
                                $select_designation=mysqli_query($conn,"SELECT * FROM tbl_designation");
                                while ($row=mysqli_fetch_assoc($select_designation)) {
                                    extract($row);
                                    echo "<option>{$designation}</option>";
                                }
                            ?> 
                        </select>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 420px;overflow-y: auto;overflow-x: auto">
                    <div id="Employee_Area">
                        <!-- zinaletwa na ajax -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                  <h4>SUB-DEPARTMENTS</h4>
                </div>
                <!-- <input type="text" id='ward_search_value' onkeyup="search_ward()"placeholder="Search ward name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption> -->
                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox' id='merge_selecteted_items'>Select All 
                            </label>
                        </td>
                        <td>
                        <select name='Department_Name' id='Department_Name' onchange='getSubDepartment(this.value)' required='required'>
                            <option selected='selected' disabled>Select Sub-Department</option>
                            <?php
                                $data = mysqli_query($conn,"select Department_ID, Department_Name from tbl_department where Department_Status = 'active'");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['Department_ID'].'>'.$row['Department_Name'].'</option>';
                                }
                            ?>    
                        </select>         
                        </td>
                        <td width="5%">
                        <a class="btn btn-default pull-right btn-sm" onclick="merge_selecteted_items()" style="text-decoration:none!important;"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-1x"></i> ASSIGN </a>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 417px;overflow-y: auto;overflow-x: hidden">
                    <div id="list_of_subdepartment">
                        <!-- zinaletwa kwa ajax -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h5>LIST OF ASSIGNED SUB DEPARTMENT</h5>
                </div>

                <input type="text" id='search_subdepartment_employee_value' onkeyup="search_subdepartment_employee()" placeholder="search by department name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>

                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_subdepartment_employee'>Select All
                            </label>
                        </td>
                        <td width="5%">
                        <a href="#" class="btn btn-default pull-right btn-sm" onclick="delete_subdepartment_employee()" style="color:red;text-decoration:none!important;"><i id="attach_cat_icon"  class="fa fa-trash fa-1x"></i> DELETE ALL SELECTED</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center class="text-info">
                                <span id="msg"></span>
                            </center>
                        </td>
                    </tr>
                </table>

                <div class="loading" style="display:none">
                    <center>
                        <img style="border: none;" src="images/please_wait_loading.gif" alt="Loading gif" class="img img-responsive">
                    </center>
                </div>
                <style>
                    .boda{border:1px solid #328CAF!important;}
                </style>
                <div class="box-body" id="box-body"  style="height: 480px; overflow-y: auto; overflow-x: hidden">
                    <div id='list_of_employee_assigned_sub_department'>
                        <table class="table table-bordered">
                            <?php 
                                $get_wards_departments = mysqli_query($conn,"SELECT DISTINCT d.Sub_Department_ID, d.Employee_ID, e.Employee_Name FROM tbl_employee_sub_department AS d, tbl_employee AS e WHERE e.Employee_ID = d.Employee_ID GROUP BY Employee_Name ORDER BY Employee_Name ASC LIMIT 10");
                                if(mysqli_num_rows($get_wards_departments) > 0){
                                while ($row1=mysqli_fetch_array($get_wards_departments)) {
                                    $Sub_Department_ID = $row1['Sub_Department_ID'];
                                    $Employee_ID = $row1['Employee_ID'];
                            ?>
                                <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
                                    <th colspan="3"><?= $row1['Employee_Name'] ?></th>
                                </tr>
                            <?php 
                             $Select_Assigned_Sub_Department = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dept, tbl_sub_department sdept, tbl_employee_sub_department ed
                                                                            where emp.employee_id = ed.employee_id and
                                                                                sdept.department_id = dept.department_id and
                                                                                    sdept.sub_department_id = ed.sub_department_id and
                                                                                        emp.employee_id = '$Employee_ID'");
                                    $count_sn=1;
                                    echo "<tr><td class='boda'> </td><td class='boda'> <b>DEPARTMENT</b> </td><td class='boda'> <b>SUB DEPARTMENT</b></td>";
                                    while($row = mysqli_fetch_array($Select_Assigned_Sub_Department)){
                                        $Employee_ID_Sub_Department_ID=$Employee_ID."-".$row['Sub_Department_ID'];
                                        echo '<tr><td class="boda"> <input type="checkbox" class="Employee_ID_Sub_Department_ID" value="'.$Employee_ID_Sub_Department_ID.'"> </td>
                                        <td class="boda">'.ucfirst($row['Department_Name']).'</td>';
                                        echo '<td class="boda">'.strtoupper($row['Sub_Department_Name']).'</td></tr>';
                                            $count_sn++;
                                                    }
                                    }
                                }else{?>
                                <tr>
                                    <td width="10%"></td>
                                    <td width="90%">
                                        <label>
                                            <b style="color: red;">NO, MERGED ITEM FOUND!</b>
                                        </label>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<!-- get sub department -->
<script type="text/javascript" language="javascript">
    function getSubDepartment(Department_ID) {
        var Editing_Employee_ID = '<?= $Editing_Employee_ID; ?>';

	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','ajax_assign_employee_subdepartment_new.php?Department_ID='+Department_ID+'&Editing_Employee_ID='+Editing_Employee_ID+"&getSubDepartment=getSubDepartment",true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('list_of_subdepartment').innerHTML = data;	
    }

    function Search_Employee(){
        var Employee_Name = document.getElementById("Employee_Name").value;
        // var Department_ID = document.getElementById("Department_ID").value;
        // var Account_Status = document.getElementById("Account_Status").value;
        
        var Designation = document.getElementById("Designation").value;
        
        // var Employee_PFNO = document.getElementById("Employee_PFNO").value;
        // var HRWork="<?php //echo (isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':''; ?>";
        
        if(window.XMLHttpRequest){
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
               
                document.getElementById("Employee_Area").innerHTML = data;
            }
        }; 
        
        myObject.open('GET','ajax_assign_employee_subdepartment_new.php?Employee_Name='+Employee_Name+'&Department_ID='+'&Designation='+Designation+"&Search_Employee=Search_Employee",true);
        myObject.send();
    }
</script>



<script>

$("#select_all_employees").click(function (e){
    $(".Employee_ID").not(this).prop('checked', this.checked); 
});

$("#merge_selecteted_items").click(function (e){
    $(".Sub_Department_ID").not(this).prop('checked', this.checked); 
});

$("#select_all_subdepartment_employee").click(function (e){
    $(".Employee_ID_Sub_Department_ID").not(this).prop('checked', this.checked); 
});

function merge_selecteted_items(){
    var Editing_Employee_ID = '<?= $Editing_Employee_ID; ?>';
    var selected_subdepartments = []; 
    $(".Sub_Department_ID:checked").each(function() {
        selected_subdepartments.push($(this).val());
    });

    var selected_employees = []; 
    $(".Employee_ID:checked").each(function() {
        selected_employees.push($(this).val());
    });

    if(selected_subdepartments==""){
        alert("Sub Department must be selected first.");
    }else if(selected_employees==""){
        alert("Employee must be selected before merging.");
    }
    else{
    //     alert('selected_subdepartments'+selected_subdepartments+' selected_employees'+selected_employees);
    // exit();
        if (confirm('Are you sure you want to merge selected items?')){
            $('.box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_assign_employee_subdepartment_new.php',
                data:{merge_selecteted_items:'merge_selecteted_items',selected_subdepartments:selected_subdepartments,selected_employees:selected_employees,Editing_Employee_ID:Editing_Employee_ID},
                success:function(data){
                    $('.loading').hide();$('.box-body').show();
                    $("#list_of_employee_assigned_sub_department").load(" #list_of_employee_assigned_sub_department"); //consider the space 
                   
                    alert('SUCCESS! \n The employee(s) was successfully assigned to selected sub-department(s).');
                    
                }
            });
        }
    }
}




function search_subdepartment_employee(){
    var search_subdepartment_employee_value = $("#search_subdepartment_employee_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_assign_employee_subdepartment_new.php',
        data:{search_subdepartment_employee_value:search_subdepartment_employee_value},
        success:function(data){
            $("#list_of_employee_assigned_sub_department").html(data); 
        }
    });
}

function delete_subdepartment_employee(){
    var Editing_Employee_ID = '<?= $Editing_Employee_ID; ?>';
    var Employee_ID_Sub_Department_ID = []; 
    $(".Employee_ID_Sub_Department_ID:checked").each(function() {
        Employee_ID_Sub_Department_ID.push($(this).val());
    });

    if(Employee_ID_Sub_Department_ID==""){
        alert("Select merged ward to delete.");
    }else{
        // alert('Employee_ID_Sub_Department_ID= '+Employee_ID_Sub_Department_ID);exit();
        if (confirm('Are you sure you want to delete? This action is irreversible!.')){
            $('#box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_assign_employee_subdepartment_new.php',
                data:{Employee_ID_Sub_Department_ID:Employee_ID_Sub_Department_ID,Editing_Employee_ID:Editing_Employee_ID},
                success:function(data){
                    $('.loading').hide();$('#box-body').show();
                    // alert(data);
                    // alert('Successfully deleted free item(s)');
                    $("#msg").html('Successfully deleted'); 
                    $("#list_of_employee_assigned_sub_department").load(" #list_of_employee_assigned_sub_department"); //consider space 
                    
                }
            });
        }
    } 
    
}



//not used
function update_remerge(selected_departments,selected_wards){
    // alert(selected_departments+','+selected_wards);
    update="update";
    $.ajax({
        type:'POST',
        url:'ajax_add_subdepartments_wards.php',
        data:{update:update,selected_departments:selected_departments,selected_wards:selected_wards},
        success:function(data){
            alert('result2= '+data);
        }
    });
}
</script>

<?php
include("./includes/footer.php");
?>
