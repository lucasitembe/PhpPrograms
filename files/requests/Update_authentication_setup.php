
<?php
    include("../includes/connection.php");
    if(isset($_POST['action'])){
     if($_POST['action']=='update'){
       $branch=$_POST['branch'];  
       $numberofDays=$_POST['numberofDays'];
       $minimumpassword=$_POST['minimumpassword'];
       $alphanumeric=$_POST['alphanumeric'];
       $account_deactivation=$_POST['account_deactivation'];
       $firstLoginChange=$_POST['firstLoginChange'];
       $query=  mysqli_query($conn,"UPDATE tbl_system_configuration SET Expire_Password_Days='$numberofDays',Allow_login_failure_Count='$account_deactivation',minimum_password_length='$minimumpassword',alphanumeric_password='$alphanumeric',Change_password_first_login='$firstLoginChange' WHERE Branch_ID='$branch'");
       if($query){
           echo 'Successfully changed!';
       }  else {
           echo 'Changing error,try again!';
       }
   }
 }
?>
