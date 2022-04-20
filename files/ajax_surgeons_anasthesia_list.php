<?php

include("./includes/connection.php");
session_start();

if(isset($_POST['opsurgeon_dialog'])){
?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <input type='text' placeholder="~~~~~~~~~~Enter Employee Name~~~~~~~~~~~~" id="Employee_Name" onkeyup='search_surgeon()' style='text-align:center'>
</div>
<div class="col-md-2">
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF'>
        <caption><b>LIST OF ALL SURGEONS</b></caption>
        <tr>
            <th>S/No.</th>
            <th>SURGEON NAME</th>
        </tr>
        <tbody id='list_of_all_surgeon'>
           
        </tbody>
    </table>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF' >
        <caption><b>LIST OF SELECTED SURGEON</b></caption>
        <tr>
            <th>S/No.</th>
            <th>SURGEON NAME</th>
        </tr>
        <tbody id='list_of_selected_surgeon'>

        </tbody>
    </table>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_list" Value="DONE" class="art-button-green pull-right" onclick="view_surgeons_selected()">
</div>
<?php }
if(isset($_POST['search_surgeon'])){
    $Employee_Name=mysqli_real_escape_string($conn,$_POST['Employee_Name']);

    $sql_search_surgeon_result=mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE  Employee_Name LIKE '%$Employee_Name%' AND Employee_Job_Code='Surgeon' LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_surgeon_result)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_surgeon_result)){
            $Employee_ID=$employee_rows['Employee_ID'];
            $Employee_Name=$employee_rows['Employee_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_surgeon($Employee_ID)'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    
                </tr>";
                $count_sn++;
        }
    }
}

if(isset($_POST['save_surgeon'])){
    if(isset($_POST['Employee_ID'])){
        $Surgeon_ID= $_POST['Employee_ID'];
        }else{
        $Surgeon_ID="";  
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
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTo tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id,Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id','$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);            
        }
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        
        $surgeon_record = mysqli_query($conn, "SELECT Surgeon_ID FROM tbl_anasthesia_surgeon WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Surgeon_ID ='$Surgeon_ID' ");
        if((mysqli_num_rows($surgeon_record))>0){
            $Surgeon_ID = mysqli_fetch_assoc($surgeon_record);
        }else{
        $sql_insert_selected_surgeon_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_surgeon(anasthesia_record_id, Employee_ID, Surgeon_ID, Registration_Id) VALUES('$anasthesia_record_id', '$Employee_ID', '$Surgeon_ID', '$Registration_ID' )") or die(mysqli_error($conn));
            if($sql_insert_selected_surgeon_result){
                echo "Saved successful";
            }else{
                echo "failed";
            }
        }

}
if(isset($_POST['view_surgeon_selected'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
        $Registration_ID="";  
    }
    $added_disease="";  
    
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }
    
    
    $sql_search_surgeon_name_result=mysqli_query($conn,"SELECT anesthesia_surgeon_id, Employee_Name FROM tbl_employee td,tbl_anasthesia_surgeon ad WHERE td.Employee_ID=ad.Surgeon_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_surgeon_name_result)>0){
    $count_sn=1;
    while($disease_rows=mysqli_fetch_assoc($sql_search_surgeon_name_result)){
        $anesthesia_surgeon_id=$disease_rows['anesthesia_surgeon_id'];
        $Employee_Name=$disease_rows['Employee_Name'];
        $added_surgeon .= "$Employee_Name, ";
    }
    }
    echo $added_surgeon;
}

if(isset($_POST['display_selected_surgen'])){
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

    
    $sql_search_surgeon_name_result=mysqli_query($conn,"SELECT anesthesia_surgeon_id, ad.Employee_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_surgeon ad WHERE td.Employee_ID=ad.Surgeon_ID AND Registration_ID='$Registration_ID'  AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_surgeon_name_result)>0){
    $count_sn=1;
    while($surgeon_row=mysqli_fetch_assoc($sql_search_surgeon_name_result)){
        $Employee_ID = $surgeon_row['Employee_ID'];
        $anesthesia_surgeon_id=$surgeon_row['anesthesia_surgeon_id'];
        $Employee_Name=$surgeon_row['Employee_Name'];
        echo "<tr class='rows_list' onclick='save_anesthesia_surgeon($Employee_ID)'>
                <td>$count_sn</td>
                <td>$Employee_Name</td>
                <td>
                    <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_surgeon($anesthesia_surgeon_id)'>
                </td>
            </tr>";
            $count_sn++;
    }
    }
}

if(isset($_POST['remove_surgeon'])){
    if(isset($_POST['anesthesia_surgeon_id'])){
        $anesthesia_surgeon_id= $_POST['anesthesia_surgeon_id'];
    }else{
        $anesthesia_surgeon_id="";  
    }

    $remove_surgeon_selected = "DELETE FROM `tbl_anasthesia_surgeon` WHERE anesthesia_surgeon_id = '$anesthesia_surgeon_id'";
    $remove_surgeon_selected_result = mysqli_query($conn, $remove_surgeon_selected);

    if($remove_surgeon_selected_result){
        echo "Removed";
    }else{
        echo "Failed";
    }
}

if(isset($_POST['search_assistant_surgeon'])){
    $Employee_Name=mysqli_real_escape_string($conn,$_POST['Employee_Name']);

    $sql_search_surgeon_result=mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE  Employee_Name LIKE '%$Employee_Name%' AND Employee_Job_Code='Surgeon' LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_surgeon_result)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_surgeon_result)){
            $Employee_ID=$employee_rows['Employee_ID'];
            $Employee_Name=$employee_rows['Employee_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_assistant_surgeon($Employee_ID)'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    
                </tr>";
                $count_sn++;
        }
    }
}



if(isset($_POST['save_assistant_surgeon'])){
    if(isset($_POST['Employee_ID'])){
        $Assistant_Surgeon_ID= $_POST['Employee_ID'];
        }else{
        $Assistant_Surgeon_ID=0;  
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
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTo tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id,Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id','$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);            
        }
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        
        $surgeon_record = mysqli_query($conn, "SELECT Assistant_Surgeon_ID FROM tbl_anasthesia_assistant_surgeon WHERE Registration_ID = '$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id' AND Assistant_Surgeon_ID ='$Assistant_Surgeon_ID' ");
        if((mysqli_num_rows($surgeon_record))>0){
            $Assistant_Surgeon_ID = mysqli_fetch_assoc($surgeon_record)['Assistant_Surgeon_ID'];
        }else{
        $sql_insert_selected_surgeon_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_assistant_surgeon(anasthesia_record_id, Employee_ID, Assistant_Surgeon_ID, Registration_ID) VALUES('$anasthesia_record_id', '$Employee_ID', '$Assistant_Surgeon_ID', '$Registration_ID' )") or die(mysqli_error($conn));
            if($sql_insert_selected_surgeon_result){
                echo "Saved successful";
            }else{
                echo "failed";
            }
        }

}

if(isset($_POST['display_assistant_selected_surgen'])){
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

    
    $sql_search_surgeon_name_result=mysqli_query($conn,"SELECT Assistant_ID, ad.Employee_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_assistant_surgeon ad WHERE td.Employee_ID=ad.Assistant_Surgeon_ID AND Registration_ID='$Registration_ID'  AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_surgeon_name_result)>0){
        $count_sn=1;
        while($surgeon_row=mysqli_fetch_assoc($sql_search_surgeon_name_result)){
            $Employee_ID = $surgeon_row['Employee_ID'];
            $Assistant_ID=$surgeon_row['Assistant_ID'];
            $Employee_Name=$surgeon_row['Employee_Name'];
            echo "<tr class='rows_list' '>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_assistant_surgeon($Assistant_ID)'>
                    </td>
                </tr>";
                $count_sn++;
        }
    }
}
    if(isset($_POST['view_assistant_surgeon_selected'])){
        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
            $Registration_ID="";  
        }
        $added_disease="";  
        
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }
        
        
        $sql_asst_surgeon_name_result=mysqli_query($conn,"SELECT Assistant_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_assistant_surgeon ad WHERE td.Employee_ID=ad.Assistant_Surgeon_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_asst_surgeon_name_result)>0){
        $count_sn=1;
        while($disease_rows=mysqli_fetch_assoc($sql_asst_surgeon_name_result)){
            $Assistant_ID=$disease_rows['Assistant_ID'];
            $Employee_Name=$disease_rows['Employee_Name'];
            $added_surgeon .= "$Employee_Name, ";
        }
        }
        echo $added_surgeon;
    }
if(isset($_POST['remove_assistant_surgeon'])){
    if(isset($_POST['Assistant_ID'])){
        $Assistant_ID= $_POST['Assistant_ID'];
    }else{
        $Assistant_ID="";  
    }

    $remove_surgeon_selected = "DELETE FROM `tbl_anasthesia_assistant_surgeon` WHERE Assistant_ID = '$Assistant_ID'";
    $remove_surgeon_selected_result = mysqli_query($conn, $remove_surgeon_selected);

    if($remove_surgeon_selected_result){
        echo "Removed";
    }else{
        echo "Failed";
    }
}
if(isset($_POST['assistant_surgeon_dialog'])){
    ?>
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <input type='text' placeholder="~~~~~~~~~~Enter Employee Name~~~~~~~~~~~~" id="Employee_Name" onkeyup='search_assistant_surgeon()' style='text-align:center'>
    </div>
    <div class="col-md-2">
    </div>
    <div class="col-md-6" style='height:400px;overflow-y:scroll'>
        <table class='table' style='background:#FFFFFF'>
            <caption><b>LIST OF ALL ASSISTANT SURGEONS</b></caption>
            <tr>
                <th>S/No.</th>
                <th>ASSISTANT SURGEON NAME</th>
            </tr>
            <tbody id='list_of_all_surgeon'>
            
            </tbody>
        </table>
    </div>
    <div class="col-md-6" style='height:400px;overflow-y:scroll'>
        <table class='table' style='background:#FFFFFF' >
            <caption><b>LIST OF SELECTED ASSISTANT SURGEON</b></caption>
            <tr>
                <th>S/No.</th>
                <th>ASSISTANT SURGEON NAME</th>
            </tr>
            <tbody id='list_of_selected_surgeon'>

            </tbody>
        </table>
    </div>
    <div class="col-md-12" id="send_data">
        <input type="button" id="send_list" Value="DONE" class="art-button-green pull-right" onclick="view_assistant_surgeons_selected()">
    </div>
<?php
}