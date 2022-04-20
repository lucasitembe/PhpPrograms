
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
?>