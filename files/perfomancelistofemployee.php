<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Employee_Collection_Report'])){
	    if($_SESSION['userinfo']['Employee_Collection_Report'] != 'yes'){
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
    
    //get section value
    if(isset($_GET['Section'])){
	$Section = $_GET['Section'];
    }else{
	$Section = '';
    }
    
    if(isset($_SESSION['userinfo'])){
        if(strtolower($Section) == 'revenuecenter'){
?>
	    <a href='employeeperfomancereport.php?Section=<?php echo $Section; ?>&PerformnaceReportCashCollection=PerformnaceReportCashCollectionThisPage' class='art-button-green'>BACK</a>
<?php
	}
    }
?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

 

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Search_List_Employee_Performance_Iframe.php?Section=<?php echo $Section; ?>&Employee_Name="+Employee_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Employee' id='Search_Employee'  oninput='searchEmployee(this.value)' placeholder='~~~~~~~~~~~~~~~~~~Search Employee Name~~~~~~~~~~~~~~~~~~~~'>
            </td> 
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>EMPLOYEES LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='Employee_Performance_Iframe.php?Section=<?php echo $Section; ?>&Employee_Name="+Employee_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>