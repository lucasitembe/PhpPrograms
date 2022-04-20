<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Product_Name'])){
        $Product_Name = $_GET['Product_Name'];   
    }else{
        $Product_Name = '';
    }
    ?>
    <script type='text/javascript'>
    function sendOrRemove(Item_ID,check_ID) {
	var action;
        var Sponsor_ID = '<?php echo $_GET['Sponsor_ID']; ?>';
        if (check_ID.checked==true){
                action = "ADD";
            }else{
                action = "REMOVE";
            }
	
	if(window.XMLHttpRequest){
	    mm_rqobject = new XMLHttpRequest();
	}
	else if(window.ActiveXObject){ 
	    mm_rqobject = new ActiveXObject('Micrsoft.XMLHTTP');
	    mm_rqobject.overrideMimeType('text/xml');
	}
	
	mm_rqobject.onreadystatechange= function (){
					var data_result = mm_rqobject.responseText;
					if (mm_rqobject.readyState==4) {
					    if (data_result=='added') {
                                                alert("ITEM ADDED SUCCESSFULLY !");
                                            }else{
                                                alert("ITEM REMOVED SUCCESSFULLY !");
                                            }
					}
				    };
            if (action=="ADD") {
                mm_rqobject.open('GET','sendOrRemoveZeroItem.php?Item_ID='+Item_ID+'&action='+action+'&Sponsor_ID='+Sponsor_ID,true);
                mm_rqobject.send();
            }else if(action=="REMOVE") {
                mm_rqobject.open('GET','sendOrRemoveZeroItem.php?Item_ID='+Item_ID+'&action='+action+'&Sponsor_ID='+Sponsor_ID,true);
                mm_rqobject.send();
            }
    }
    </script> 
    <?php
    $category_condition = '';
    if(isset($_GET['Item_Category_ID'])){
	if($_GET['Item_Category_ID']!='ALL'){
		$category_condition = "and ic.Item_category_ID = ".$_GET['Item_Category_ID'];
	}
    }
    $qr = "SELECT * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
					where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
					    isc.Item_category_ID = ic.Item_category_ID and
                                                Visible_Status <> 'Others' and
                                                    i.Product_Name like '%$Product_Name%' ".$category_condition;
    echo '<center><table width =100% border=0>';
    echo "<tr id='thead' ><td style='width:5%;'><b>SN</b></td>
		<td><b>CATEGORY</b></td>
		<td><b>TYPE</b></td>
		    <td><b>PRODUCT NAME</b></td>
		        <td><b>SPONSOR SUPPORT</b></td></tr>";
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    if(isset($_GET['Sponsor_ID'])){
            if($_GET['Sponsor_ID']!=''){
                while($row = mysqli_fetch_array($select_Filtered_Patients)){
                    $qr_check_if_selected = "SELECT * FROM tbl_sponsor_allow_zero_items WHERE sponsor_id=".$_GET['Sponsor_ID']." AND item_ID=".$row['Item_ID'];
                    $check_qr_result = mysqli_query($conn,$qr_check_if_selected);
                    $checked = false;
                    if(mysqli_num_rows($check_qr_result)>0){
                        $checked = true;
                    }
                    echo "<tr><td id='thead'>".$temp."</td>"; 
                    echo "<td>".$row['Item_Category_Name']."</td>";
                    echo "<td>".$row['Consultation_Type']."</td>";
                    echo "<td>".$row['Product_Name']."</td>";
                    ?><td><input type='checkbox' <?php if($checked){ ?>checked='checked'<?php } ?> onclick="sendOrRemove('<?php echo $row['Item_ID']; ?>',this)" >Allow Zero Price</td><?php
                    $temp++;
                    echo "</tr>";
                }   
            }else{
                    echo "<tr><td colspan='5' ><center><br>Choose Sponsor<br></center></td></tr>";
                }   
    }else{
            echo "<tr><td colspan='5' ><center><br>Choose Sponsor<br></center></td></tr>";
        }   
?>
</table>
</center>