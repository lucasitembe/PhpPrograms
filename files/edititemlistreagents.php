 <script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        
        if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])) {
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
                    if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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



<form action='#' method='post' name='myForm' id='myForm' >

<script>
	function getItemsList(Item_Category_ID){
		document.getElementById("Search_Value").value = ''; 
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
		//alert(data);
	    
		myObject.onreadystatechange = function (){
			    data = myObject.responseText;
			    if (myObject.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Items_Fieldset').innerHTML = data;
			    }
			}; //specify name of function that will handle server response........
		myObject.open('GET','Get_List_Of_Requisition_Items_List.php?Item_Category_ID='+Item_Category_ID,true);
		myObject.send();
	}
	    
	function getItemsListFiltered(Item_Name){
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		if (Item_Category_ID == '' || Item_Category_ID == null) {
		    Item_Category_ID = 'All';
		}
		
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
	    
		myObject.onreadystatechange = function (){
			    data = myObject.responseText;
			    if (myObject.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Items_Fieldset').innerHTML = data;
			    }
			}; //specify name of function that will handle server response........
		myObject.open('GET','Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
		myObject.send();
	}
</script>
<br/>
<fieldset>
    <legend align='center'><b>EDIT REAGENT ITEM</b></legend>
    <center>    
        <table width=60%>
            <tr>
                <td style='text-align: center;'>
                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                        <option selected='selected'></option>
                        <?php
                            $data = mysqli_query($conn,"select * from tbl_reagents_category") or die(mysqli_error($conn));
                            while($row = mysqli_fetch_array($data)){
                                echo '<option value="'.$row['Reagent_Category_ID'].'">'.$row['Reagent_Name'].'</option>';
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='~~~~~~~~~~~~~~~~~~Search Item~~~~~~~~~~~~~~~~~~' style='text-align: center;'>
                </td>
            </tr>			    
            <tr>
                <td colspan=2>
                    <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                        <table width=100%>
                            <tr>
                                <td><b>CATEGORY NAME</b></td>
                                <td><b>ITEM NAME</b></td>
                            </tr>
                            <?php
                                $result = mysqli_query($conn,"SELECT * FROM tbl_reagents_items ri, tbl_reagents_category rc where
                                                        ri.Reagent_Category_ID = rc.Reagent_Category_ID order by Product_Name") or die(mysqli_error($conn));
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>
                                            <a href='editreorderitem.php?Item_ID=".$row['Item_ID']."&EditReagentItem=EditReagentThisPage' style='text-decoration: none;''>".$row['Reagent_Name']."</a>
                                            </td>";
                                    echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>
                                            <a href='editreorderitem.php?Item_ID=".$row['Item_ID']."&EditReagentItem=EditReagentThisPage' style='text-decoration: none;''>".$row['Product_Name']."</a>
                                            </td></tr>";
                                }
                            ?> 
                        </table>
                    </fieldset>		
                </td>
            </tr>
        </table>
    </center>
</fieldset>


<?php
  include("./includes/footer.php");
?>