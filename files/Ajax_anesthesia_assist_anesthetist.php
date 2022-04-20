<?php include("./includes/connection.php");
session_start();

if(isset($_POST['dialog_assist_anesthetist'])){
?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <input type='text' placeholder="~~~~~Search Assistant Anasthetist Name~~~~~" onkeyup='search_assist_anesthetist()' id="assistant_name" style='text-align:center'>
</div>
<div class="col-md-2">
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll' id="background_anesthetist">
    <table class='table table-bordered' style='background:#FFFFFF'>
        <caption><b>LIST OF ALL ASSISTANT ANESTHETIST</b></caption>
        <tr>
            <th>S/No.</th>
            <th>ASSISTANT ANESTHETIST NAME</th>
        </tr>
        <tbody id='list_of_all_assistant'>
 
        </tbody> 
    </table>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF' >
        <caption><b>LIST OF SELECTED ASSISTANT ANESTHETIST</b></caption>
        <tr>
            <th>S/No.</th>
            <th>ASSISTANT ANESTHETIST NAME</th>
        </tr>
        <tbody id='list_of_selected_assistant'>

        </tbody>
    </table>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_assist_anesthetist_selected()">
</div>
<?php 
} 

if(isset($_POST['search_assist'])){

    $Employee_Name=mysqli_real_escape_string($conn,$_POST['Employee_Name']);

    $sql_search_assist_anesthetist_result=mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE  Employee_Name LIKE '%$Employee_Name%'  LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_assist_anesthetist_result)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_assist_anesthetist_result)){
            $Employee_ID=$employee_rows['Employee_ID'];
            $Employee_Name=$employee_rows['Employee_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_assist_anesthetist($Employee_ID)'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    
                </tr>";  
                $count_sn++;
        }
    }
}

if(isset($_POST['save_assist_anesthetist'])){
    if(isset($_POST['Employee_ID'])){ 
        $Assist_anesthetist_ID= $_POST['Employee_ID'];
        }else{
        $Assist_anesthetist_ID="";   
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
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id,Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id','$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);            
        }

        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $assist_anasthetist_record = mysqli_query($conn, "SELECT Assist_anesthetist_ID FROM tbl_anasthesia_assist_anasthetist WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' AND Assist_anesthetist_ID ='$Assist_anesthetist_ID' ");
        if((mysqli_num_rows($assist_anasthetist_record))>0){
            $Assist_anesthetist_ID = mysqli_fetch_assoc($assist_anasthetist_record);
        }else{
        $sql_insert_selected_anesthetist_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_assist_anasthetist(anasthesia_record_id,Assist_anesthetist_ID, Employee_ID, Registration_Id) VALUES('$anasthesia_record_id', '$Assist_anesthetist_ID','$Employee_ID', '$Registration_ID' )") or die(mysqli_error($conn));
            if($sql_insert_selected_anesthetist_result){
                echo "saved";
            }else{
                echo "failed";
            }
        }
}

if(isset($_POST['view_assist_anesthetist'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
    $Registration_ID="";  
    }
    $added_assist_anestheologist="";  
    
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }
    

    $sql_search_assist_anestheologist_name_result=mysqli_query($conn,"SELECT assist_anasthetist_id, Employee_Name FROM tbl_employee td,tbl_anasthesia_assist_anasthetist ad WHERE td.Employee_ID=ad.Assist_anesthetist_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_assist_anestheologist_name_result)>0){
    $count_sn=1;
    while($assist_anestheologist_row=mysqli_fetch_assoc($sql_search_assist_anestheologist_name_result)){
        $assist_anasthetist_id=$assist_anestheologist_row['assist_anasthetist_id'];
        $Employee_Name=$assist_anestheologist_row['Employee_Name'];
        $added_assist_anestheologist .= "$Employee_Name, ";
    }
    }
    echo $added_assist_anestheologist; 
}

if(isset($_POST['display_selected'])){
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

    $sql_search_assist_anesthetist_name_result=mysqli_query($conn,"SELECT assist_anasthetist_id, td.Employee_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_assist_anasthetist ad WHERE td.Employee_ID=ad.Assist_anesthetist_ID AND Registration_ID='$Registration_ID'  AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_assist_anesthetist_name_result)>0){
        $count_sn=1;
        while($assistant_row=mysqli_fetch_assoc($sql_search_assist_anesthetist_name_result)){
            $assist_anasthetist_id=$assistant_row['assist_anasthetist_id'];
            $Employee_Name=$assistant_row['Employee_Name'];
            echo "<tr class='rows_list'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_assist_anesthetist($assist_anasthetist_id)'>
                    </td>
                </tr>";
                $count_sn++;
        }
    }
}

if(isset($_POST['remove_assist_anesthetist'])){
    if(isset($_POST['assist_anasthetist_id'])){
        $assist_anasthetist_id= $_POST['assist_anasthetist_id'];
    }else{
        $assist_anasthetist_id="";  
    }

    $remove_assist_anesthetist_selected = "DELETE FROM `tbl_anasthesia_assist_anasthetist` WHERE assist_anasthetist_id = '$assist_anasthetist_id'";
    $remove_assist_anesthetist_selected_result = mysqli_query($conn, $remove_assist_anesthetist_selected); 
    if($remove_assist_anesthetist_selected_result){
        echo "Removed";
    }else{
        echo "failed";
    }
}