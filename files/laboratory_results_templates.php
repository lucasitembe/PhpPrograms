<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&item_id=".filter_input(INPUT_GET, 'item_id')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."' class='art-button-green'>BACK</a>";
            }
    }
   
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>
<br/><br/>
<br/><br/>
<fieldset>
        <legend align=center><b>LABORATORY RESULTS TEMPLATES</b></legend>
        <center>
	    <table width = 60%>

	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
			?>
			<a href='laboratory_general_template.php?Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']?>&Status_From=<?php echo filter_input(INPUT_GET, 'Status_From'); ?>&patient_id=<?php echo filter_input(INPUT_GET, 'patient_id'); ?>&Item_ID=<?php echo filter_input(INPUT_GET, 'Item_ID'); ?>&payment_id=<?php echo filter_input(INPUT_GET, 'payment_id'); ?>'>
			    <button style='width: 100%; height: 100%'>
				General Template
			    </button>
			</a>
		    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            General Template 
                        </button>
		    <?php } ?>
                </td>
            </tr>

        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            ?>
            <a href='#'>
                <button style='width: 100%; height: 100%'>
                Culture Template
                </button>
            </a>
            <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Culture Template 
                        </button>
            <?php } ?>
                </td>
            </tr>


	        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			?>
			<a href='#'>
			    <button style='width: 100%; height: 100%'>
				Histogram Template
			    </button>
			</a>
		    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Histogram Template 
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