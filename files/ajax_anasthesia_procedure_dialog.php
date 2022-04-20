<?php 

include("./includes/connection.php");
session_start();

if(isset($_POST['procedure_dialog'])){
?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <input type='text' placeholder="~~~~~Search procedure Name~~~~~" onkeyup='ajax_search_procedure()' id="procedure_name" style='text-align:center'>
</div>
<div class="col-md-2">
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll' id="background_procedure">
    <table class='table table-bordered' style='background:#FFFFFF'>
        <caption><b>LIST OF ALL PROCEDURE</b></caption>
        <tr>
            <th>S/No.</th>
            <th>Procedure NAME</th>
        </tr>
        <tbody id='list_of_all_procedure'>
 
        </tbody>
    </table>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF' >
        <caption><b>LIST OF SELECTED PROCEDURE</b></caption>
        <tr>
            <th>S/No.</th>
            <th>PROCEDURE NAME</th>
        </tr>
        <tbody id='list_of_selected_procedure'>

        </tbody>
    </table>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_procedure_selected()">
</div>

<?php }

if(isset($_POST['search_procedure'])){
    $Product_Name=mysqli_real_escape_string($conn,$_POST['Product_Name']);

    $sql_search_procedure=mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name LIKE '%$Product_Name%' AND (Consultation_Type='Procedure' OR Consultation_Type='Surgery') LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_procedure)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_procedure)){
            $Item_ID=$employee_rows['Item_ID'];
            $Product_Name=$employee_rows['Product_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_procedure($Item_ID)'>

                    <td>$count_sn</td>
                    <td>$Product_Name</td>
                    
                </tr>";  
                $count_sn++;
        }
    }
}

if(isset($_POST['save_procedure'])){
    if(isset($_POST['Item_ID'])){
        $Item_ID= $_POST['Item_ID'];
        }else{
        $Item_ID="";   
        }
 


        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";   
        }
        if(isset($_POST['Payment_Cache_ID'])){
            $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        }else{
            $Payment_Cache_ID= "";
        }
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id, Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id', '$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
        }
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        $procedure_record = mysqli_query($conn, "SELECT Item_ID FROM tbl_anasthesia_procedure WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Item_ID ='$Item_ID' ");
        if((mysqli_num_rows($procedure_record))>0){
            $Item_ID = mysqli_fetch_assoc($procedure_record);
        }else{
        $sql_insert_selected_procedure_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_procedure(created_at, anasthesia_record_id, Item_ID, Employee_ID, Registration_Id) VALUES(NOW(), '$anasthesia_record_id', '$Item_ID','$Employee_ID', '$Registration_ID' )") or die(mysqli_error($conn));
        }
}

if(isset($_POST['display_procedure'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
    $Registration_ID="";  
    } 
    
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' ORDER BY anasthesia_record_id DESC LIMIT 1";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }

    $sql_search_procedure_result=mysqli_query($conn,"SELECT Procedure_ID, Product_Name FROM tbl_items td,tbl_anasthesia_procedure ad WHERE td.Item_ID=ad.Item_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_procedure_result)>0){
    $count_sn=1;
    while($procedure_rows=mysqli_fetch_assoc($sql_search_procedure_result)){
        $Procedure_ID=$procedure_rows['Procedure_ID'];
        
        $Product_Name=$procedure_rows['Product_Name'];
        echo "<tr class='rows_list'>
                <td>$count_sn</td>
                <td>$Product_Name</td>
            
                <td>
                    <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_procedure($Procedure_ID)'>
                </td>
            </tr>";
            $count_sn++;
    }
    }
}

if(isset($_POST['remove_procedure'])){
    if(isset($_POST['Procedure_ID'])){
        $Procedure_ID= $_POST['Procedure_ID'];
    }else{
        $Procedure_ID="";  
    }

    $remove_procedure_selected = "DELETE FROM `tbl_anasthesia_procedure` WHERE Procedure_ID = '$Procedure_ID'";
    $remove_procedure_selected_result = mysqli_query($conn, $remove_procedure_selected);
    if($remove_procedure_selected_result){
        echo "removed";
    }else{
        echo "failed";
    }
}

if(isset($_POST['view_procedure'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
    $Registration_ID="";  
    }
    $added_procedure="";  
    
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }
    

    $sql_search_procedure_name_result=mysqli_query($conn,"SELECT Procedure_ID, Product_Name FROM tbl_items td,tbl_anasthesia_procedure ad WHERE td.Item_ID=ad.Item_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_procedure_name_result)>0){
    $count_sn=1;
    while($procedure_row=mysqli_fetch_assoc($sql_search_procedure_name_result)){
        $Procedure_ID=$procedure_row['Procedure_ID'];
        $Product_Name=$procedure_row['Product_Name'];
        $added_procedure .= "$Product_Name, ";
    }
    }
    echo $added_procedure; 
}