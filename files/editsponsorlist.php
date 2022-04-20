<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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

    $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT edit_sponsor FROM tbl_privileges WHERE edit_sponsor='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<script language="javascript" type="text/javascript">
    function searchSponsor(Guarantor_Name){
	document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=430px src='edit_Sponsor_Pre_Iframe.php?Guarantor_Name="+Guarantor_Name+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Employee' id='Search_Employee'  onclick='searchSponsor(this.value)' onkeyup='searchSponsor(this.value)' placeholder='Enter Employee Name'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>LIST OF SPONSORS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=430px src='edit_Sponsor_Pre_Iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>