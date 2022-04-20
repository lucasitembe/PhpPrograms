<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Pharmacy'])){
	if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
<?php
    //get department location
    if(isset($_GET['SessionCategory'])){
	$SessionCategory = $_GET['SessionCategory'];
    }else{
	$SessionCategory = '';
    }
?>
<?php
	if(isset($_POST['submittedSupervisorInformationForm'])){
            $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            $Sub_Department_Name = $_POST['Sub_Department_Name'];
            
            $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and be.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";
            
            //DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
		if($SessionCategory == 'Radiology'){
		    $_SESSION['Radiology_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Radiology_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Radiology'] = $Sub_Department_Name;
		    audit($_SESSION['Radiology_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./radiologyworkspage.php?RadiologyWorkPage=RadiologyWorkPageThisPage");
		}
		
		if($SessionCategory == 'Rch'){
		    $_SESSION['Rch_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Rch_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Rch'] = $Sub_Department_Name;
		    audit($_SESSION['Rch_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./rchworkspage.php?RchWorkPage=RchWorkPageThisPage");
		}
		
		if($SessionCategory == 'Hiv'){
		    $_SESSION['Hiv_Supervisor'] = $row;
		    $_SESSION['Hiv_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Hiv_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Hiv'] = $Sub_Department_Name;
		    audit($_SESSION['Hiv_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./hivworkspage.php?RchWorkPage=RchWorkPageThisPage");
		}
		
		if($SessionCategory == 'Family_Planning'){
		    $_SESSION['Family_Planning_Supervisor'] = $row;
		    $_SESSION['Family_Planning_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Family_Planning_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Hiv'] = $Sub_Department_Name;
		    audit($_SESSION['Family_Planning_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./family_planningworkspage.php?Family_PlanningWorkPage=Family_PlanningWorkPageThisPage");
		}
		
		if($SessionCategory == 'Dental'){
		    $_SESSION['Dental_Supervisor'] = $row;
		    $_SESSION['Dental_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Dental_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Dental'] = $Sub_Department_Name;
		    audit($_SESSION['Dental_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./dentalworkspage.php?DentalWorkPage=RchWorkPageThisPage");
		}
		
		if($SessionCategory == 'Ear'){
		    $_SESSION['Ear_Supervisor'] = $row;
		    $_SESSION['Ear_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Ear_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Ear'] = $Sub_Department_Name;
		    audit($_SESSION['Ear_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./earworkspage.php?EarWorkPage=EarWorkPageThisPage");
		}
		
		//maternity supervisor authentication
		if($SessionCategory == 'Maternity'){
		    $_SESSION['Maternity_Supervisor'] = $row;
		    $_SESSION['Maternity_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Maternity_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Maternity'] = $Sub_Department_Name;
		    audit($_SESSION['Maternity_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./maternityworkspage.php?MaternityWorkPage=MaternityWorkPageThisPage");
		}
		
		//Dialysis supervisor auth
		if($SessionCategory == 'Dialysis'){
		    $_SESSION['Dialysis_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Dialysis_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Dialysis'] = $Sub_Department_Name;
		    audit($_SESSION['Dialysis_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./dialysisworkspage.php?DialysisWorkPage=DialysisWorkPageThisPage");
		}
		
		//physiotherapy supervisor auth
		if($SessionCategory == 'Physiotherapy'){
		    $_SESSION['Physiotherapy_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Physiotherapy_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Physiotherapy'] = $Sub_Department_Name;
		    audit($_SESSION['Physiotherapy_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./physiotherapyworkspage.php?PhysiotherapyWorkPage=PhysiotherapyWorkPageThisPage");
		}
		
		//Optical supervisor auth
		if($SessionCategory == 'Eye'){
		    $_SESSION['Eye_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Eye_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Eye'] = $Sub_Department_Name;
		    audit($_SESSION['Eye_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./eyeworkspage.php?EyeWorkPage=EyeWorkPageThisPage");
		}
		
		//Dressing supervisor auth
		if($SessionCategory == 'Dressing'){
		    $_SESSION['Dressing_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Dressing_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Dressing'] = $Sub_Department_Name;
		    audit($_SESSION['Dressing_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./dressingworkspage.php?DressingWorkPage=DressingWorkPageThisPage");
		}
		//Theater supervisor auth
		if($SessionCategory == 'Theater'){
		    $_SESSION['Theater_Supervisor'] = $row;
		    $_SESSION['Theater_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Theater_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Theater'] = $Sub_Department_Name;
		    audit($_SESSION['Theater_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./theaterworkspage.php?TheaterWorkPage=TheaterWorkPageThisPage");
		}
		
		//Cecap supervisor auth
		if($SessionCategory == 'Cecap'){
		    $_SESSION['Cecap_Supervisor'] = $row;
		    $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
		    
		    //get sub department
		    $sub_dep_result = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		    $row = mysqli_fetch_array($sub_dep_result);
		    $Sub_Department_ID = $row['Sub_Department_ID'];
		    
		    $Branch_ID = $_SESSION['Cecap_Supervisor']['Branch_ID'];
		    
		    $_SESSION['Cecap'] = $Sub_Department_Name;
		    audit($_SESSION['Cecap_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID);
		    header("Location:./cecapworkspage.php?CecapWorkPage=CecapWorkPageThisPage");
		}
		
                
                
            }
            else { 
                echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
            }
	}
?>



<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b><?php echo str_replace('_',' ',strtoupper($SessionCategory)); ?> SUPERVISOR AUTHENTICATION</b></legend>
                    <table width = '100%'>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                                <tr>
                                    <td width=30%><b>Supervisor Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><b>Supervisor Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr>
                                <tr>
                                    <td><b>Sub Department</b></td>
                                    <td>
										<select>
											<option selected='selected'>Laboratory 1</option>
										</select
                                        <?php /*<!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                                            <option selected='selected'></option>
                                            <?php
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }
                                                $select_sub_departments = mysqli_query($conn,"select Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = '".str_replace('_',' ',$SessionCategory)."'
                                                                                        ");
                                                while($row = mysqli_fetch_array($select_sub_departments)){
                                                    echo "<option>".$row['Sub_Department_Name']."</option>";
                                                }
                                            
                                            ?>
                                        </select> */?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <!--<input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' class='art-button-green'>-->
										
										<a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage' class='art-button-green'>ALLOW AUTHENTICATION</a>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>



<?php
    include("./includes/footer.php");
?>