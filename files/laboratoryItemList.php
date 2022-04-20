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
?>

            <!-- link menu -->


            <?php

					  if(isset($_SESSION['userinfo'])){
						if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
							{ 
							echo "<a href='laboratory_setup.php' class='art-button-green'>BACK</a>";
							}
					}  
			?>


	<script type='text/javascript'>
		function access_Denied(){ 
			   alert("Access Denied");
					  document.location = "./index.php";
		}
		</script>


		<script language="javascript" type="text/javascript">
			function searchItem(Product_Name){
				document.getElementById('Search_Iframe').innerHTML = 
			"<iframe width='100%' height=420px src='laboratory_item_list_iframe.php?Product_Name="+Product_Name+"'></iframe>";
			}
		</script>

		<br/>
		<br/>
<center>
	<table style="width:40%;margin-top:5px;">
		<tr>
			<td>
				<input type='text' name='Search_Item' id='Search_Item' oninput='searchItem(this.value)'  placeholder='~~~~~~~~~~~~~~~~~~~Search Product Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
			</td>

		</tr>

	</table>
</center>
	<br>
	<fieldset>  
	<legend align="right" style="background-color:#006400;color:white;padding:5px;">

		<b>
			 MANAGE TEST AVAILABILITY
		</b>
	</legend>
	<center>
		<table width='100%' border=1>
			<tr>
			<td id='Search_Iframe'>
						<iframe width='100%' height=390px src='laboratory_item_list_iframe.php'></iframe>
			</td>
		</tr>
	</table>
</center>
</fieldset>
<br/>
<?php
	include("./includes/footer.php");
?>