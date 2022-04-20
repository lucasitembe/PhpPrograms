<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $_SESSION['Dashboard_Control'] = 'Revenue Center';
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
    	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
     if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
     }else{
        $Section = '';
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



<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>SUPERVISOR AUTHENTICATION</b></legend>
                    <table width = '100%'>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=30% style="text-align:right;"><b>Supervisor Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' autocomplete='off' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td style="text-align:right;"><b>Supervisor Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr> 
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']).' TO PERFORM TRANSACTIONS'; ?>' class='art-button-green'>
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
	if(isset($_POST['submittedSupervisorInformationForm'])){
            $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            
            $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and e.employee_id = be.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";
            
            //DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
	    
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
                $_SESSION['supervisor']=$row;
                if(strtolower($Section) == 'msamaha'){
                    header("Location:./credittransactions.php?Section=Msamaha&CreditTransactions=CreditTransactionsThisForm");
                }else{
                    header("Location:./revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage");
                }
            }
            else { 
                echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
            }
	}
?>
<?php
    include("./includes/footer.php");
?>