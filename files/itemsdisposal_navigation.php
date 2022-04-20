<script src='js/functions.js'></script>
<?php
    if(isset($_SESSION['userinfo'])){
		echo "<a href='itemsdisposal_session_clearer.php?New_Disposal=True' class='art-button-green'>NEW DISPOSAL</a>";
	}
	
    if(isset($_SESSION['userinfo'])){
		echo "<a href='itemsdisposalpending.php?PendingDisposals=PendingDisposalsThisPage' class='art-button-green'>PENDING</a>";
	}
	
	if(isset($_SESSION['userinfo'])){
		echo "<a href='itemsdisposalsaved.php?PreviousDisposals=PreviousDisposalsThisPage' class='art-button-green'>PREVIOUS</a>";
	}
		
	if(isset($_SESSION['userinfo'])){
		echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
	}
?>