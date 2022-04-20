
<?php
    include("./includes/connection.php");
        session_start(); 
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
?>