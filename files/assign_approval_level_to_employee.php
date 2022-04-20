<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="employee_approval_configuration.php" class="art-button-green">BACK</a>
<style>
    #attach_cat_icon{
        transform: rotate(45deg);
    }
</style>
<br/>
<fieldset>  
            <legend align=center><b>ASSIGN APPROVAL LEVEL TO EMPLOYEE</b></legend>
            <table class="table">
                <td>Approval Document</td>
                <td>
                    <select class="form-control" id="document_type_to_approve"onchange="fill_approval_level_title()"> 
                        <option value="">---------Select Document Type------------------</option> 
                        <option value="grn_against_issue_note">GRN Against Issue Note</option> 
                        <option value="grn_against_purchases_order">GRN Against Purchase Order</option> 
                        <option value="grn_without_purchases_order">GRN Without Purchases Order</option> 
                        <option value="grn_physical_counting_as_open_balance">GRN As Open Balance/Physical Counting</option> 
                        <option value="purchase_requisition">Purchase Requisition</option> 
                        <option value="purchase_order">Local Purchase Order</option> 
                        <option value="issue_note">Issue Note</option> 
                        <option value="requisition">Requisition</option> 
                        <option value="store_order">Store Order</option> 
                        <option value="bill_approval">Bill Approval</option> 
                        <option value="bill_creation">Bill Creation</option>
                        <option value="purchase_without_order">Local Without Purchase Order</option> 
                    </select>
                </td>
                <td>Approval Level Title</td>
                <td>
                    <select class="form-control" id="document_approval_level_title_id" onchange="get_selected_document_assigen_approval_employee()">
                        <option value="">--------Select Approval Level Title--------</option>
                        
                    </select>
                </td>
                
            </table>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h4 class="box-title">Assign employee title and document to approve</h4>
                        </div>
                        <div class="box-body">
                            <input type="text" style="text-align:center" onkeyup="search_list_of_employee()" id="Employee_Name" placeholder="---------------------------Search Employee Name---------------------------" class="form-control"/>
                            <div style="height:300px;overflow-y: scroll;overflow-x: hidden">
                                <table class="table" id="list_of_all_employee">
                                    <tr>
                                        <td style="width:50px"><b>S/No</b></td>
                                        <td style="width:80%"><b>Employee Name</b></td>
                                        <td style="width:50px"><b>Action</b></td>
                                    </tr>
                                    <?php 
                                        $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Account_Status='active'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_employee_result)>0){
                                            $count=1;
                                            while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                                               $Employee_Name=$employee_rows['Employee_Name'];
                                               $Employee_ID=$employee_rows['Employee_ID'];
                                               echo "
                                                   <tr>
                                                        <td>$count</td>
                                                        <td>$Employee_Name</td>
                                                        <td>
                                                            <input type='button' class='art-button-green' onclick='assign_employee_approval_level(\"$Employee_ID\")' value='ASSIGN'/>
                                                        </td>
                                                    </tr>
                                                    ";
                                               $count++;
                                            }
                                        }
                                    ?> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h4 class="box-title" id="assigned_approval_level_title_header"></h4>
                            <div style="height: 354px;overflow-y: scroll;overflow-x: hidden" class="box-body">
                                <table class="table" id="list_of_employee_assigned_approval_level">
                                    
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</fieldset>
<script>
    function assign_employee_approval_level(Employee_ID){
        var document_approval_level_title_id=$("#document_approval_level_title_id").val();
        var document_type_to_approve=$("#document_type_to_approve").val();
        if(document_type_to_approve==""){
            $("#document_type_to_approve").css("border","2px solid red");
            $("#document_type_to_approve").focus();
            exit;
        }else{
            $("#document_type_to_approve").css("border","");  
        }
        if(document_approval_level_title_id==""){
            $("#document_approval_level_title_id").css("border","2px solid red");
            $("#document_approval_level_title_id").focus();
            exit;
        }else{
            $("#document_approval_level_title_id").css("border","");  
        }
        $.ajax({
            type:'GET',
            url:'ajax_assign_approval_level_to_employee.php',
            data:{Employee_ID:Employee_ID,document_approval_level_title_id:document_approval_level_title_id,document_type_to_approve:document_type_to_approve},
            success:function (data){
              console.log(data);  
              get_selected_document_assigen_approval_employee()
            }
        });
    }
    function fill_approval_level_title(){
       var document_type_to_approve=$("#document_type_to_approve").val();
       $.ajax({
            type:'GET',
            url:'fill_approval_level_title.php',
            data:{document_type_to_approve:document_type_to_approve},
            success:function (data){
                $("#document_approval_level_title_id").html(data);
                //console.log(data);  
            }
        }); 
    }
    function get_selected_document_assigen_approval_employee(){
        var document_approval_level_title_id = document.getElementById("document_approval_level_title_id");
        var document_approval_level_title= document_approval_level_title_id.options[document_approval_level_title_id.selectedIndex].text;
        var document_type_to_approve = document.getElementById("document_type_to_approve");
        var document_type= document_type_to_approve.options[document_type_to_approve.selectedIndex].text;
        
      $("#assigned_approval_level_title_header").html(""+document_type+"~~"+document_approval_level_title)
      
      var document_approval_level_title_id=$("#document_approval_level_title_id").val();
      var document_type_to_approve=$("#document_type_to_approve").val();
       $.ajax({
            type:'GET',
            url:'get_selected_document_assigen_approval_employee.php',
            data:{document_type_to_approve:document_type_to_approve,document_approval_level_title_id:document_approval_level_title_id},
            success:function (data){
                $("#list_of_employee_assigned_approval_level").html(data);
                console.log(data);  
            }
        });  
    }
    function remove_this_employee_approval_level(assigned_approval_level_id){
      $.ajax({
            type:'GET',
            url:'remove_this_employee_approval_level.php',
            data:{assigned_approval_level_id:assigned_approval_level_id},
            success:function (data){
                 get_selected_document_assigen_approval_employee();
            }
        });   
    }
    function search_list_of_employee(){
        var Employee_Name=$("#Employee_Name").val();
        $.ajax({
            type:'GET',
            url:'search_list_of_employee.php',
            data:{Employee_Name:Employee_Name},
            success:function (data){
               $("#list_of_all_employee").html(data);  
            }
        });
    } 
</script>
<?php
    include("./includes/footer.php");
?>
