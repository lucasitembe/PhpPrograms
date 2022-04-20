<?php
session_start();
include("../includes/connection.php");
 if(isset($_POST['action'])){
       if($_POST['action']=='save'){
           $msamahaName= mysqli_real_escape_string($conn,$_POST['msamahaName']);
           $Check = mysqli_query($conn,"SELECT * FROM tbl_msamaha_items WHERE msamaha_aina='$msamahaName'");
           $num_rows=  mysqli_num_rows($Check);
           if($num_rows>0){
              $Query= mysqli_query($conn,"SELECT * FROM tbl_msamaha_items");
                echo '<select name="Aina_ya_msamaha" id="Aina_ya_msamaha" style="padding:4px;width:99%" required><option></option>';
                while ($row=  mysqli_fetch_assoc($Query)){
                    echo '<option>'.$row['msamaha_aina'].'</option>'; 

                 }
                 echo '</select>'; 
           }else{
           $qr2 = "INSERT INTO tbl_msamaha_items (msamaha_aina) VALUES('$msamahaName')";
            $result_item = mysqli_query($conn,$qr2);
            if($result_item){
                $Query= mysqli_query($conn,"SELECT * FROM tbl_msamaha_items");
                   echo '<select name="Aina_ya_msamaha" id="Aina_ya_msamaha" style="padding:4px;width:99%" required><option></option>';
                while ($row=  mysqli_fetch_assoc($Query)){
                    echo '<option>'.$row['msamaha_aina'].'</option>'; 

                 }
                 echo '</select>';
                
            }
            
           }
            
      }
   }
   
?>
