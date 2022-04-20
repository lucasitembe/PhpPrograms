<?php include("./includes/connection.php");
session_start();

if(isset($_POST['dialog_anasthetist'])){
?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <input type='text' placeholder="~~~~~Search Anasthetist Name~~~~~" onkeyup='search_anesthetist()' id="anesthetist_name" style='text-align:center'>
</div>
<div class="col-md-2">
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll' id="background_anesthetist">
    <table class='table table-bordered' style='background:#FFFFFF'>
        <caption><b>LIST OF ALL ANESTHETIST</b></caption>
        <tr>
            <th>S/No.</th>
            <th>ANESTHETIST NAME</th>
        </tr>
        <tbody id='list_of_all_anesthetist'>
 
        </tbody>
    </table>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF' >
        <caption><b>LIST OF SELECTED ANESTHETIST</b></caption>
        <tr>
            <th>S/No.</th>
            <th>ANESTHETIST NAME</th>
        </tr>
        <tbody id='list_of_selected_anesthetist'>

        </tbody>
    </table>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_anesthetist_selected()">
</div>

<?php }

if(isset($_POST['search_anesthetist'])){
    $Employee_Name=mysqli_real_escape_string($conn,$_POST['Employee_Name']);

    $sql_search_anesthetist_result=mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE  Employee_Name LIKE '%$Employee_Name%' AND Employee_Job_Code='Anaesthesiologist' LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_anesthetist_result)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_anesthetist_result)){
            $Employee_ID=$employee_rows['Employee_ID'];
            $Employee_Name=$employee_rows['Employee_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_anesthetist($Employee_ID)'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    
                </tr>"; 
                $count_sn++;
        }
    }
}

if(isset($_POST['save_anesthetist'])){
    if(isset($_POST['Employee_ID'])){
        $Anasthetist_ID= $_POST['Employee_ID'];
        }else{
        $Anasthetist_ID="";   
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
        $anasthetist_record = mysqli_query($conn, "SELECT Anasthetist_ID FROM tbl_anasthesia_anesthetist WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Anasthetist_ID ='$Anasthetist_ID' ");
        if((mysqli_num_rows($anasthetist_record))>0){
            $Anasthetist_ID = mysqli_fetch_assoc($anasthetist_record);
        }else{
        $sql_insert_selected_anesthetist_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_anesthetist(anasthesia_record_id, Anasthetist_ID, Employee_ID, Registration_Id) VALUES('$anasthesia_record_id', '$Anasthetist_ID','$Employee_ID', '$Registration_ID' )") or die(mysqli_error($conn));
            if($sql_insert_selected_anesthetist_result){
                echo "saved";
            }else{
                echo "Failed";
            }
        }
}

if(isset($_POST['view_anesthetist'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
    $Registration_ID="";  
    }
    $added_anestheologist="";  
    
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }
    

    $sql_search_anestheologist_name_result=mysqli_query($conn,"SELECT anesthesia_anesthetist_id, Employee_Name FROM tbl_employee td,tbl_anasthesia_anesthetist ad WHERE td.Employee_ID=ad.Anasthetist_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_anestheologist_name_result)>0){
        $count_sn=1;
        while($anestheologist_row=mysqli_fetch_assoc($sql_search_anestheologist_name_result)){
            $anesthesia_anesthetist_id=$anestheologist_row['anesthesia_anesthetist_id'];
            $Employee_Name=$anestheologist_row['Employee_Name'];
            $added_anestheologist .= "$Employee_Name, ";
        }
    }
    echo $added_anestheologist; 
}

if(isset($_POST['display_anesthetist'])){
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

    $sql_search_anesthetist_name_result=mysqli_query($conn,"SELECT anesthesia_anesthetist_id, td.Employee_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_anesthetist ad WHERE td.Employee_ID=ad.Anasthetist_ID AND Registration_ID='$Registration_ID'  AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_anesthetist_name_result)>0){
        $count_sn=1;
        while($anesthetist_row=mysqli_fetch_assoc($sql_search_anesthetist_name_result)){
            $anesthesia_anesthetist_id=$anesthetist_row['anesthesia_anesthetist_id'];
            $Employee_Name=$anesthetist_row['Employee_Name'];
            echo "<tr class='rows_list' onclick='save_anasthesia_anesthetist($Employee_ID)'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_anesthetist($anesthesia_anesthetist_id)'>
                    </td>
                </tr>";
                $count_sn++;
        }
    }
}

if(isset($_POST['remove_anesthetist'])){
    if(isset($_POST['anesthesia_anesthetist_id'])){
        $anesthesia_anesthetist_id= $_POST['anesthesia_anesthetist_id'];
    }else{
        $anesthesia_anesthetist_id="";  
    }

    $remove_anesthetist_selected = "DELETE FROM `tbl_anasthesia_anesthetist` WHERE anesthesia_anesthetist_id = '$anesthesia_anesthetist_id'";
    $remove_anesthetist_selected_result = mysqli_query($conn, $remove_anesthetist_selected); 
    if($remove_anesthetist_selected_result){
        echo "Removed";
    }else{
        echo "failed";
    }
}