<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='./systemaudit.php?SystemAusit=SystemAuditThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type="text/javascript" language="javascript">
    function getEmployeeList(branchID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','getEmployeeList.php?branchID='+branchID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('employeeID').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
    <!--Script to display filter-->
    <script>
        //function to display login range
        function LoginRange(){
            if(document.getElementById("Login").checked){
                document.getElementById("LoginRange").style.display="block";
            }else{
                document.getElementById("LoginRange").style.display="none";
            }
        }

        //function to display logout range
        function LogoutRange(){

            if(document.getElementById("Logout").checked){
                document.getElementById("LogoutRange").style.display="block";
            }else{
                document.getElementById("LogoutRange").style.display="none";
            }
        }

        //function to display authentication range
        function AuthenticationRange(){
            if(document.getElementById("Authentication").checked){
                document.getElementById("AuthenticationRange").style.display="block";
            }else{
                document.getElementById("AuthenticationRange").style.display="none";
            }
        }

        //function to display Activity range
        function ActivityLogRange(){
            if(document.getElementById("ActivityLog").checked){
                document.getElementById("ActivityLogRange").style.display="block";
            }else{
                document.getElementById("ActivityLogRange").style.display="none";
            }
        }

    </script>
<br/><br/>




<fieldset style="overflow-y: scroll;height: 450px;">

    <center>
        <form action='systemauditFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width='100%'>
                <tr>
                    <td style="text-align: center"><b>Branch</b></td>
                    <td style="text-align: center">
                        <select name="branchID" id="branchID" onchange='getEmployeeList(this.value)'>
                            <option selected="selected" value="0">All</option>
                            <?php
                            //run the query to display branches
                            $select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
                            while($select_branch_row=mysqli_fetch_array($select_branch)){
                                $branchID=$select_branch_row['Branch_ID'];
                                $branchName=$select_branch_row['Branch_Name'];

                                echo "<option value='$branchID'>$branchName</option>";

                            }
                            ?>
                        </select>
                    </td>
                    <td style="text-align: center"><b>Employee</b></td>
                    <td style="text-align: center">
                        <select name='employeeID' id='employeeID'>
                            <option selected='selected' value='0'>All</option>

                        </select>
                        </select>
                    <td>
                        <div style="text-align: center">
                            <input type="checkbox" name="Login" id="Login" onclick="LoginRange()"><b>Login Date And Time</b>
                        </div>
                        <div id="LoginRange" style="display: none;">
                            <table>
                                <tr>
                                    <td style="text-align: left"><b>From</b></td>
                                    <td style="text-align: left">
                                        <input style="text-align: left" type='text' name='Login_From' id='Login_From'>
                                    </td>
                                    <td style="text-align: left"><b>To</b></td>
                                    <td style="text-align: left"><input type='text' name='Login_To' id='Login_To'></td>
                                </tr>
                            </table>
                        </div>
                    </td>

                    <td>
                        <div style="text-align: center">
                            <input type="checkbox" name="Logout" id="Logout" onclick="LogoutRange()"><b>Logout Date And Time</b>
                        </div>
                        <div id="LogoutRange" style="display: none;">
                            <table>
                                <tr>
                                    <td style="text-align: left"><b>From</b></td>
                                    <td style="text-align: left">
                                        <input style="text-align: left" type='text' name='Logout_From' id='Logout_From' >
                                    </td>
                                    <td style="text-align: left"><b>To</b></td>
                                    <td style="text-align: left"><input type='text' name='Logout_To' id='Logout_To' ></td>
                                </tr>
                            </table>
                        </div>
                    </td>

                    <td>
                        <div style="text-align: center">
                            <input type="checkbox" name="Authentication" id="Authentication" onclick="AuthenticationRange()"><b>Authentication Date And Time</b>
                        </div>
                        <div id="AuthenticationRange" style="display: none;">
                            <table>
                                <tr>
                                    <td style="text-align: left"><b>From</b></td>
                                    <td style="text-align: left">
                                        <input style="text-align: left" type='text' name='Auth_From' id='Auth_From' >
                                    </td>
                                    <td style="text-align: left"><b>To</b></td>
                                    <td style="text-align: left"><input type='text' name='Auth_To' id='Auth_To' ></td>
                                </tr>
                            </table>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        <div style="text-align: center">-->
<!--                            <input type="checkbox" name="ActivityLog" id="ActivityLog" onclick="ActivityLogRange()"><b>Activity Performed</b>-->
<!--                        </div>-->
<!--                        <div id="ActivityLogRange" style="display: none;">-->
<!--                            <table>-->
<!--                                <tr>-->
<!--                                    <td style="text-align: left"><b>From</b></td>-->
<!--                                    <td style="text-align: left">-->
<!--                                        <input style="text-align: left" type='text' name='Activity_From' id='Activity_From' >-->
<!--                                    </td>-->
<!--                                    <td style="text-align: left"><b>To</b></td>-->
<!--                                    <td style="text-align: left"><input type='text' name='Activity_To' id='Activity_To' ></td>-->
<!--                                </tr>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </td>-->
                    <td style='text-align: center;'>
                        <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                    </td>
                </tr>
            </table>
        </form>
    </center>
    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
        $('#Login_From').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:	'now'
        });
        $('#Login_From').datetimepicker({value:'',step:30});
        $('#Login_To').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:'now'
        });
        $('#Login_To').datetimepicker({value:'',step:30});
    </script>

    <script>
        $('#Logout_From').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:	'now'
        });
        $('#Logout_From').datetimepicker({value:'',step:30});
        $('#Logout_To').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:'now'
        });
        $('#Logout_To').datetimepicker({value:'',step:30});
    </script>

    <script>
        $('#Auth_From').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:	'now'
        });
        $('#Auth_From').datetimepicker({value:'',step:30});
        $('#Auth_To').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:'now'
        });
        $('#Auth_To').datetimepicker({value:'',step:30});
    </script>
    <script>
        $('#Activity_From').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:	'now'
        });
        $('#Activity_From').datetimepicker({value:'',step:30});
        $('#Activity_To').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:'now'
        });
        $('#Activity_To').datetimepicker({value:'',step:30});
    </script>



    <?php
	if(isset($_POST['Print_Filter'])){
        $branchID=mysqli_real_escape_string($conn,$_POST['branchID']);
        $employeeID=mysqli_real_escape_string($conn,$_POST['employeeID']);
        $Login_From=mysqli_real_escape_string($conn,$_POST['Login_From']);
        $Login_To=mysqli_real_escape_string($conn,$_POST['Login_To']);
        $Logout_From=mysqli_real_escape_string($conn,$_POST['Logout_From']);
        $Logout_To=mysqli_real_escape_string($conn,$_POST['Logout_To']);
        $Auth_From=mysqli_real_escape_string($conn,$_POST['Auth_From']);
        $Auth_To=mysqli_real_escape_string($conn,$_POST['Auth_To']);
    }else{
        $branchID='';
        $employeeID='';
        $Login_From='';
        $Login_To='';
        $Logout_From='';
        $Logout_To='';
        $Auth_From='';
        $Auth_To='';
    }
    ?>
    <legend align=center><b>SYSTEM AUDIT REPORT </b> <b><?php //echo date('j F, Y H:i:s',strtotime($Date_From))?></b><b> TO </b><b><?php //echo date('j F, Y',strtotime($Date_To))?></b></legend>
        <!--include the systemAuditFilter-->
        <iframe src="systemauditFilter_Iframe.php?branchID=<?php echo $branchID?>&employeeID=<?php echo $employeeID?>&Login_From=<?php echo $Login_From?>&Login_To=<?php echo $Login_To?>&Logout_From=<?php echo $Logout_From?>&Logout_To=<?php echo $Logout_To?>&Auth_From=<?php echo $Auth_From?>&Auth_To=<?php echo $Auth_To?>" width="100%" height="450px"></iframe>
</fieldset>
			
				<table>
					<tr>
					    <td style='text-align: center;'>
                        <a href="previewSystemAuditFilter.php?branchID=<?php echo $branchID?>&employeeID=<?php echo $employeeID?>&Login_From=<?php echo $Login_From?>&Login_To=<?php echo $Login_To?>&Logout_From=<?php echo $Logout_From?>&Logout_To=<?php echo $Logout_To?>&Auth_From=<?php echo $Auth_From?>&Auth_To=<?php echo $Auth_To?>&PreviewSystemAuditFilterThisPage=ThisPage" target="_blank" name='previewSystemAuditFilter' id='previewSystemAuditFilter' class='art-button-green'>
						    PRINT
						</a>
					    </td>
					</tr>
				    </table>
<?php
    include("./includes/footer.php");
?>