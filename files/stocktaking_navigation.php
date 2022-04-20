<script src='js/functions.js'></script>
<?php
    if(isset($_SESSION['userinfo'])){
		echo "<a href='stocktaking_session_clearer.php?New_Disposal=True' class='art-button-green'>NEW STOCK TAKING</a>";
	}
	
    if(isset($_SESSION['userinfo'])){
		echo "<a href='stocktakingpending.php?StockingTakingPending=StockingTakingPendingThisPage' class='art-button-green'>PENDING</a>";
	}
	
	if(isset($_SESSION['userinfo'])){
		echo "<a href='stocktakingsaved.php?StockingTakingSaved=StockingTakingSavedThisPage' class='art-button-green'>PREVIOUS</a>";
	}
		
	if(isset($_SESSION['userinfo'])){
		echo "<a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
	}
?>