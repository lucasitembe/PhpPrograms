<link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="css_style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
        <script src="js/tabcontent.js" type="text/javascript"></script>
    


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<?php
    include("./includes/connection.php"); 
	if(isset($_GET['Requisition_ID'])){
	    $Requisition_ID = $_GET['Requisition_ID'];
	}else{
	    $Requisition_ID = 0;
	}
	
    echo '<center><table width = 100% border=0>';
    echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td width=7% style="text-align: center;"><b>Quantity</b></td>
			    <td width=25% style="text-align: center;"><b>Remark</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, rqi.Quantity_Required, rqi.Item_Remark
					    from tbl_requisition_items rqi, tbl_items itm where
						itm.Item_ID = rqi.Item_ID and
						    rqi.Requisition_ID ='$Requisition_ID'") or die(mysqli_error($conn)); 

    $Temp=1;
    while($row = mysqli_fetch_array($select_Transaction_Items)){ 
	echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
	echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
	echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
	echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
	echo "<td width=6%><a href='#' class='art-button-green'>Remove</a></td>";
	echo "</tr>";
	$Temp++;
    }
?></table></center>