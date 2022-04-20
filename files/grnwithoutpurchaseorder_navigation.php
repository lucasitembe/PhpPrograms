<style>
	.badge {
		position: absolute;
		top: -10px;
		right: -4px;
		padding: 5px 8px;
		border-radius: 50%;
		background: red;
		color: white;
	}
</style>

<?php
	$counts = $count;
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='Session_Control_Grn_Without_Perchase_Order.php?Status=New'
			        class='art-button-green' style='font-family:arial'>CREATE NEW GRN</a>";

			echo "<a href='previousgrnwithoutpurchaseorder.php?PreviousGrnWithoutPurchaseOrder=PreviousGrnWithoutPurchaseOrderThisPage'
			        class='art-button-green' style='font-family:arial'>PREVIOUS</a>";
			echo "<a href='approve_grn_without_purchases_order.php'
			    	 class='art-button-green' style='font-family:arial'>NEW GRN <span class='badge'>$numbers</span> </a>";
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>
