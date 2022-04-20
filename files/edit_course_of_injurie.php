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
                        $courseInjuryID = mysqli_real_escape_string($conn,$_POST['courseInjuryID']);
               
		$sql = "UPDATE tbl_hospital_course_injuries
                            SET course_injury='$courseinjury',date_saved=NOW(),Employee_ID='".$_SESSION['userinfo']['Employee_ID']."' WHERE Branch_ID='".$_SESSION['userinfo']['Branch_ID']."'  AND hosp_course_injury_ID='".$courseInjuryID."'";
                
                $check_if_any=  mysqli_query($conn,"SELECT course_injury FROM tbl_hospital_course_injuries WHERE Branch_ID='".$_SESSION['userinfo']['Branch_ID']."'") or die(mysqli_error($conn));
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
			    alert('COURSE OF INJURY UPDATED SUCCESSFUL');
                            window.location='courseofinjurylist.php?courseofinjurielistConfigurations=courseofinjurielistConfigurationsThisForm';
			    </script>"; 
		}
                //}
	}
        
        $course_injury='';
         $cons=  mysqli_query($conn,"SELECT * FROM tbl_hospital_course_injuries WHERE Branch_ID='".$_SESSION['userinfo']['Branch_ID']."' AND hosp_course_injury_ID='".$_GET['hosp_course_injury_ID']."'") or die(mysqli_error($conn));
	$row=  mysqli_fetch_assoc($cons);
        
        $course_injury=$row['course_injury'];
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
                                        <input name="courseinjury" style="padding: 4px;font-size:15px;width: 90%" value="<?php echo $course_injury?>"/>
                                          
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;padding-left:150px '>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedcourseinjurForm' value='true'/> 
                                        <input type="hidden" name="courseInjuryID" value="<?php echo $_GET['hosp_course_injury_ID'];?>"/>
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
      
</center>


<?php
    include("./includes/footer.php");
?>