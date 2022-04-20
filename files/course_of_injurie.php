<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href='courseinjurypage.php' class='art-button-green'>
          BACK
    </a>

<br/><br/>

<?php
	if(isset($_POST['submittedcourseinjurForm'])){
		        $courseinjury = mysqli_real_escape_string($conn,$_POST['courseinjury']);
               
		$sql = "insert into tbl_hospital_course_injuries(
                            course_injury,Branch_ID,
                            date_saved,Employee_ID)

                            values('$courseinjury','".$_SESSION['userinfo']['Branch_ID']."',NOW(),
                                        '".$_SESSION['userinfo']['Employee_ID']."')";
                
              
//		
//                if(mysqli_num_rows($check_if_any)>0){
//                     $result=  mysqli_query($conn,"UPDATE tbl_hospital_course_injuries SET course_injury='$courseinjury',date_saved=NOW(),Employee_ID='".$_SESSION['userinfo']['Employee_ID']."' WHERE Branch_ID='".$_SESSION['userinfo']['Branch_ID']."'") or die(mysqli_error($conn));
//		       echo "<script type='text/javascript'>
//			    alert('HOSPITAL DOCTOR CONSULTATION TYPE UPDATED SUCCESSFUL');
//                             window.location='doctor_consult_arch.php?setarchitecture=setarchitectureThisPage';
//			    </script>"; 
//                }else{
                
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('COURSE OF INJURY ALREADY EXISTS! \nTRY ANOTHER NEW ONE');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('COURSE OF INJURY ADDED SUCCESSFUL');
                            window.location='course_of_injurie.php?courseofinjurieConfigurations=courseofinjurieConfigurationsThisForm';
			    </script>"; 
		}
                //}
	}
        
        
?>

<br/><br/>
<center>
    <fieldset style="width:80% ">
                    <legend align="center" ><b>SET COURSE OF INJURY</b></legend>
                    <table style="width:60% " border="0">
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Course of injury</b></td>
                                    <td width=80%>
                                        <input name="courseinjury" style="padding: 4px;font-size:15px;width: 90%" />
                                          
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;padding-left:150px '>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedcourseinjurForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
      
</center>


<?php
    include("./includes/footer.php");
?>