
<!--
<tr>
    <td style='text-align: center; height: 40px; width: 33%;'>
	<?php if($Purchase_Session == 'yes'){ ?>
	<a href='departmentalpurchaseorder.php?status=new&NPO=True&SessionCategory=<?php echo $Session_Category; ?>&PurchaseOrder=PurchaseOrderThisPage'>
	    <button style='width: 100%; height: 100%'>
		Purchase Orders
	    </button>
	</a>
	<?php }else{ ?>
	 
	    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
		Purchase Orders 
	    </button>
      
	<?php } ?>
    </td>
</tr>
-->
<tr>
    <td style='text-align: center; height: 40px; width: 33%;'>
	<?php if($Purchase_Session == 'yes'){ ?>
	<a href='goodreceivednote.php?SessionCategory=<?php echo $Session_Category; ?>&GoodReceivedNote=GoodReceivedNoteThisPage'>
	    <button style='width: 100%; height: 100%'>
		Goods Receiving Note
	    </button>
	</a>
	<?php }else{ ?>
	 
	    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
		Goods Receiving Note 
	    </button>
      
	<?php } ?>
    </td>
</tr>
<tr>
    <td style='text-align: center; height: 40px; width: 33%;'>
	<?php if($Purchase_Session == 'yes'){ ?>
	<a href='issuenotes.php?SessionCategory=<?php echo $Session_Category; ?>&IssueNotes=IssueNotesThisPage'>
	    <button style='width: 100%; height: 100%'>
		Issue Note
	    </button>
	</a>
	<?php }else{ ?>
	 
	    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
		Issue Note
	    </button>
      
	<?php } ?>
    </td>
</tr>