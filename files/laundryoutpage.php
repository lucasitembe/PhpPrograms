<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Dialysis_Works'])){
	    if($_SESSION['userinfo']['Dialysis_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ 
?>
    <a href='laundry_in_out.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>


<script language="javascript" type="text/javascript">
    function searchTrans(laundry_in_or_out_ID){
        document.getElementById('Search_Iframe').innerHTML = 
		"<iframe width='100%' height=320px src='laundryoutpage_iframe.php?laundry_in_or_out_ID="+laundry_in_or_out_ID+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width="40%">
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient'  onkeyup='searchTrans(this.value)'
				 placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~Enter Transaction No~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>LISTS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height="320px"  src='laundryoutpage_iframe.php?laundry_in_or_out_ID=' ></iframe>
            </td>
				</tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>