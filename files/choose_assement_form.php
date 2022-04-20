<?php 
    $indexPage = true;
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
    
    <input type="button" value="BACK" class="art-button-green" onclick="history.go(-1)">
<?php 
 } }

 $Registration_ID = $_GET['Registration_ID'];
 $consultation_ID = $_GET['consultation_ID'];
 $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
 $Today_Date = mysqli_query($conn,"select now() as today");
 while ($row = mysqli_fetch_array($Today_Date)) {
     $original_Date = $row['today'];
     $new_Date = date("Y-m-d", strtotime($original_Date));
     $Today = $new_Date;
 }
 $patient_detail = mysqli_query($conn,"SELECT Patient_Name, Registration_ID, Date_of_Birth, Tribe, Region, Email_Address, Phone_Number FROM tbl_patient_registration WHERE Registration_ID=$Registration_ID") or die(mysqli_error($conn));
    if((mysqli_num_rows($patient_detail))>0){
         while($patient_infor_row = mysqli_fetch_assoc($patient_detail)){
             $patient_name = $patient_infor_row['Patient_Name'];
             $patient_number = $patient_infor_row['Registration_ID'];
             $Date_Of_Birth = $patient_infor_row['Date_of_Birth'];

             $date1 = new DateTime($Today);
             $date2 = new DateTime($Date_Of_Birth);
             $diff = $date1->diff($date2);
             $age = $diff->y;
         }
    }
    // echo $Date_Of_Birth;
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

               
<br/><br/>                
<br/><br/>                
<br/><br/>                
<fieldset>
    <legend align=center><b>OCCUPATION THERAPY WORKS</b></legend>
        <center>
			
        <table width = 90%>
            <?php 
            if($age>18){?>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <a  href="adult_assement_form.php?Registration_ID=<?php echo $Registration_ID;?>&consultation_ID=<?php echo $consultation_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>">
                        <button style='width: 80%; height: 100%'>
                            ADULT ASSESMENT FORM
                        </button> 
                </a>
                </td>
            </tr>
            <?php }else { ?>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <a  href="pediatric_form.php?Registration_ID=<?php echo $Registration_ID;?>&consultation_ID=<?php echo $consultation_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>">
                        <button style='width: 80%; height: 100%'>
                        PEDIATRIC ASSEEMENT FORM
                        </button>
                    </a> 
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <a  href="psychatric_form.php?Registration_ID=<?php echo $Registration_ID;?>&consultation_ID=<?php echo $consultation_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>">
                        <button style='width: 80%; height: 100%'>
                        PSYCHATRIC ASSEEMENT FORM
                        </button>
                    </a> 
                </td>
            </tr>
        </table></center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>