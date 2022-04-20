<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	//$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	unset($_SESSION['Issue_ID']);
	if(!isset($_SESSION['userinfo'])){
		@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Pharmacy'])){
			if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}


	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
			echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>

<br/><br/><br/><br/><br/><br/>
<!--get sub department naem-->
<?php
    if(isset($_SESSION['Pharmacy_ID'])){
	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
    
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
	while($data = mysqli_fetch_array($select)){
	    $Sub_Department_Name = $data['Sub_Department_Name'];
	}
    }else{
	$Sub_Department_Name = '';
    }
?>
<script>
    function access_Denied(){
        alert("Access Denied");
    }
</script>
<fieldset>  
            <legend align = 'right'><b><?php if(isset($_SESSION['Pharmacy_ID'])){ echo strtoupper($Sub_Department_Name); } ?> ~  GOOD RECEIVING NOTE</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                    <a href='pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Against Issue Note
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Against Issue Note 
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                     <?php 
                                    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                                    $sql_check_for_bakup_privilage="SELECT can_have_access_to_grn_physical_counting FROM tbl_privileges WHERE can_have_access_to_grn_physical_counting='yes' AND Employee_ID='$Employee_ID'";
                                     $sql_check_for_privilage_result=mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error($conn));
                                     
                                    ?>
                    <?php
//                    if($_SESSION['userinfo']['Pharmacy'] == 'yes'&&mysqli_num_rows($sql_check_for_privilage_result)>0)
                    if($_SESSION['userinfo']['Pharmacy'] == 'yes')
                        { 
                    ?>
                    <a href='pharmacygrnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage'>
                        <button style='width: 100%; height: 100%'>
                         GRN As Open Balance / Physical Counting
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
			GRN As Open Balance / Physical Counting
                        </button>
                    <?php } ?>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>