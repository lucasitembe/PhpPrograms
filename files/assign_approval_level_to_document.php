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
            <legend align=center><b>ASSIGN APPROVAL LEVEL TO DOCUMENT</b></legend>
     <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center" disabled="disabled"class="form-control"placeholder=""/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Document Approval Level</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_category_to_main_department()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_document_approval_level_body">
                        <table class="table">
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_document_approval_level_title") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($approve_level_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $document_approval_level_title_id=$approve_level_rows['document_approval_level_title_id'];
                                    $document_approval_level_title=$approve_level_rows['document_approval_level_title'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='document_approval_level_title_id' name='document_approval_level_title_id' value='$document_approval_level_title_id'>$document_approval_level_title
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-5">
                    <label>SELECT DOCUMENT TYPE</label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control" id="document_type_to_approve"onchange="refresh_content()"> 
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

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br/>
                    <div class="box box-primary" style="height: 400px;overflow: auto">
                        <div class="box-header">
                            <div class="col-sm-12"><p id="approval_level_list_tittle" style="font-size:17px">Approval Level attached to </p></div>
                        </div>
                        <div class="box-body" id="attached_approval_level_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<script>
     $("#select_all_checkbox").click(function (e){
         $(".document_approval_level_title_id").not(this).prop('checked', this.checked); 
         
     });    
     function attach_category_to_main_department(){
        var selectedApprovel_level = []; 
        $(".document_approval_level_title_id:checked").each(function() {
		selectedApprovel_level.push($(this).val());
                //console.log($(this).val());
	});
        var document_type_to_approve=$("#document_type_to_approve").val()
        if(document_type_to_approve==""){
          $("#document_type_to_approve").css("border","1px solid red");
          alert("Select Document Type to Assign Approval Level");
          exit;
        }else{
          $("#document_type_to_approve").css("border","");  
        }
        $.ajax({
            type:'POST',
            url:'ajax_assign_approve_level_to_document.php',
            data:{selectedApprovel_level:selectedApprovel_level,document_type_to_approve:document_type_to_approve},
            success:function(data){
                $("#attached_approval_level_body").html(data);
                refresh_content()
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         var document_type_to_approve=$("#document_type_to_approve").val();
         $("#select_all_checkbox").prop("checked",false)

         var sel = document.getElementById("document_type_to_approve");
         var idara_name= sel.options[sel.selectedIndex].text;
         if(document_type_to_approve!="")$("#approval_level_list_tittle").html("Approval Level attached to <b>"+idara_name+"</b>")
         $.ajax({
             type:'POST',
             url:'refresh_assigned_approval_level_list.php',
             data:{document_type_to_approve:document_type_to_approve},
             success:function(data){
               $("#attached_approval_level_body").html(data);  
             }
         });
         $.ajax({
             type:'POST',
             url:'refresh_approval_list.php',
             data:{document_type_to_approve:document_type_to_approve},
             success:function(data){
                  $("#all_document_approval_level_body").html(data); 
             }
         });
     }
     function remove_approval_level(document_approval_level_id){
       $.ajax({
             type:'POST',
             url:'remove_document_approval_level.php',
             data:{document_approval_level_id:document_approval_level_id},
             success:function(data){
                  refresh_content(); 
             }
         }); 
    }
</script>
<?php
    include("./includes/footer.php");
?>
