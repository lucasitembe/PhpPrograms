<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE edit_items='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
?>
<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$count_price_to_merge=0;
if(isset($_POST['merge_btn'])){
  $to_sponsor_id=$_POST['to_sponsor_id'];
  $from_sponsor_id=$_POST['from_sponsor_id'];
  $Item_Category_ID2=$_POST['Item_Category_ID2'];
  $category_filter="";
  if($Item_Category_ID2!="All"){
      $category_filter="AND isb.Item_category_ID='$Item_Category_ID2'";
  }
  
  //first delete all price belong to this sponsor
  $sql_delete_this_sponsor_price_result=mysqli_query($conn,"DELETE FROM tbl_item_price WHERE Sponsor_ID='$to_sponsor_id'") or die(mysqli_error($conn));
  $sql_select_item_price="SELECT ip.Sponsor_ID,ip.Item_ID,ip.Items_Price FROM tbl_item_price ip INNER JOIN tbl_items i ON ip.Item_ID=i.Item_ID INNER JOIN tbl_item_subcategory isb ON i.Item_Subcategory_ID=isb.Item_Subcategory_ID WHERE ip.Sponsor_ID='$from_sponsor_id' $category_filter";
  $sql_select_item_price_result=mysqli_query($conn,$sql_select_item_price) or die(mysqli_error($conn));
  $count_merged_price=0;
  $count_price_to_merge=0;
  if(mysqli_num_rows($sql_select_item_price_result)>0){
      while($item_rows=mysqli_fetch_assoc($sql_select_item_price_result)){
          $Item_ID=$item_rows['Item_ID'];
          $Items_Price=$item_rows['Items_Price'];
          $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
          $sql_insert_item_price="INSERT INTO tbl_item_price(Sponsor_ID,Item_ID,Items_Price,last_updated_by) VALUES('$to_sponsor_id','$Item_ID','$Items_Price','$Employee_ID')  ON DUPLICATE KEY UPDATE Items_Price='$Items_Price',last_updated_by='$Employee_ID'";
          $sql_insert_item_price_result=mysqli_query($conn,$sql_insert_item_price) or die(mysqli_error($conn));
          if($sql_insert_item_price_result){
             $count_merged_price++; 
          }
          $count_price_to_merge++;
      }
  }
 
  
   $getSponsor = "SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$from_sponsor_id'";
		    $sponsor_result = mysqli_query($conn,$getSponsor);
                     $row3 = mysqli_fetch_assoc($sponsor_result);
                      $from_sponsor=$row3['Guarantor_Name'];
     
  
   $getSponsor2 = "SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$to_sponsor_id'";
		    $sponsor_result2 = mysqli_query($conn,$getSponsor2);
                     $row32 = mysqli_fetch_assoc($sponsor_result2);
                      $to_sponsor=$row32['Guarantor_Name'];
                    
}


if($count_price_to_merge>0){
$message=" $count_merged_price item price from $from_sponsor successfully merged to $count_price_to_merge  item From $to_sponsor";

echo "<script>
     alert('$message');
</script>";
}
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes'){
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes'){
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes'){
?>
    <a href='addnewitemcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes'){
?>
    <a href='itemsmanagement.php' class='art-button-green'>
        ITEM MANAGE
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
?>
    <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
        REVENUE CENTER
    </a>
<?php  } } ?>

                    
<?php
    //display back button based on location
    if(isset($_GET['Section'])){
	if(strtolower($_GET['Section']) == 'reception'){
	    echo "<a href='receptionpricelist.php?PriceList=PriceListThisPage' class='art-button-green'>
		    BACK
		</a>";
	}elseif(strtolower($_GET['Section']) == 'revenuecenter'){
	    echo "<a href='pricelist.php?PriceList=PriceListThisPage' class='art-button-green'>
		    BACK
		</a>";
	}
    }elseif(isset($_GET['FromRevenue'])){
    if($_GET['FromRevenue']=='Revenues'){
       echo "<a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
        BACK
    </a>";  
    } 
 }else{
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
		echo "<a href='edititems.php?EditItem=EditItemThisForm' class='art-button-green'>
			BACK
		    </a>";
	    }
	}
    }
?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?> 
<br/><br/>
<center>
    <table width='100%'>
        <tr>
            <td id="item_price_filter_option">
         <table>
             <tr>
            <div>
	    <td>Category :</td>
	    <td>
		<select id='Item_Category_ID' style='padding:3px' name='Item_Category_ID'>
                    <option value="All">ALL</option>
		    <?php
		    $cat_qr = "SELECT * FROM tbl_item_category";
		    $cat_result = mysqli_query($conn,$cat_qr);
		    while($cat_row = mysqli_fetch_assoc($cat_result)){
			?>
			<option value="<?php echo $cat_row['Item_Category_ID']; ?>"><?php
			echo $cat_row['Item_Category_Name'];
			?></option>
			<?php
		    }
		    ?>
		</select>
	    </td>
            <td>
                <select style='padding:3px' id="Consultation_Type">
                    <option value="All">Select Consultation Type</option>
                   <option>Pharmacy</option>
                   <option>Laboratory</option>
                   <option>Radiology</option>
                   <option>Surgery</option>
                   <option>Procedure</option>
                   <option>Optical</option>
                   <option>Others</option>
                </select>
            </td>
            <td style="text-align: right;">
                Sponsor Name
            </td>
            <td width='15%'>
                <select id="Sponsor_Name" style="width:100%;padding:3px">
                    <option value="All">SELECT SPONSOR</option>
                    <?php
                     $getSponsor = "SELECT * FROM tbl_sponsor";
		    $sponsor_result = mysqli_query($conn,$getSponsor);
                    while($row3 = mysqli_fetch_assoc($sponsor_result)){
                      echo '<option value="'.$row3['Sponsor_ID'].'">'.$row3['Guarantor_Name'].'</option>';
                     }
                    ?>
                    
                </select>
            </td>
            <td width='15%'>
                <input type="text" oninput="getItemName()" style="padding:3px; text-align: center;" id='itemClr' placeholder="~~~ ~~~ Search Item Name ~~~ ~~~">
            </td>
            <td width='15%'>
                <input type="text" oninput="getItemName()" style="padding:3px; text-align: center;" id='itemCode' placeholder="~~~ ~~~ Search Item Code ~~~ ~~~">
            </td>
            <td width="10%">
              &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="Solid_Option" id="Solid_Option" value="yes" <?php if(isset($_SESSION['Include_Non_Solid_Items']) && $_SESSION['Include_Non_Solid_Items'] = 'yes'){ echo "checked='checked'"; } ?> onclick="Include_Non_Solid(this)">
              <label for="Solid_Option"><b>Includes Non Solid Items</b></label>
            </td>
           </tr>
       </table>
    </td>
    <td style="display: none" id="merge_price_tbl">
        <form action="" method="post">
    <table>
            <tr>
             <td>Category</td>
	    <td>
		<select id='Item_Category_ID2' style='padding:3px' name='Item_Category_ID2'>
                    <option value="All">ALL</option>
		    <?php
		    $cat_qr = "SELECT * FROM tbl_item_category";
		    $cat_result = mysqli_query($conn,$cat_qr);
		    while($cat_row = mysqli_fetch_assoc($cat_result)){
			?>
			<option value="<?php echo $cat_row['Item_Category_ID']; ?>"><?php
			echo $cat_row['Item_Category_Name'];
			?></option>
			<?php
		    }
		    ?>
		</select>
	    </td>
            <td style="text-align: right;" width='20%' >
               Merge price from Sponsor Name
            </td>
            <td width='15%'>
                <select id="Sponsor_Name2"  name="from_sponsor_id" style="width:100%;padding:3px">
                  <!--  <option value="All">GENERAL</option>-->
                    <?php
                     $getSponsor = "SELECT * FROM tbl_sponsor";
		    $sponsor_result = mysqli_query($conn,$getSponsor);
                    while($row3 = mysqli_fetch_assoc($sponsor_result)){
                      echo '<option value="'.$row3['Sponsor_ID'].'">'.$row3['Guarantor_Name'].'</option>';
                     }
                    ?>
                    
                </select>
            </td>
            <td style="text-align: right;" width='15%'>
               Merge price to Sponsor Name
            </td>
            <td width='15%'>
                <select id="" name="to_sponsor_id" style="width:100%;padding:3px">
                    <?php
                    $getSponsor = "SELECT * FROM tbl_sponsor where auto_item_update_api<>'1'";
		    $sponsor_result = mysqli_query($conn,$getSponsor);
                    while($row3 = mysqli_fetch_assoc($sponsor_result)){
                        $Sponsor_ID=$row3['Sponsor_ID'];
                        //$sql_check_sponsor_id="SELECT Sponsor_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID'";
                       // $sql_check_sponsor_id_result=mysqli_query($conn,$sql_check_sponsor_id);
                      //  if(mysqli_num_rows($sql_check_sponsor_id_result)>0)continue;;
                      echo '<option value="'.$row3['Sponsor_ID'].'">'.$row3['Guarantor_Name'].'</option>';
                     }
                    ?>
                    
                </select>
            </td>
            <td colspan="3" width='15%'>
                <button type="submit" name="merge_btn"onclick="return confirm('Are you sure you want to merge the price ?')" style="align-content:center;height:35px!important;color: #FFFFFF!important"class='art-button-green'>MERGE</button>
            </td>
        </tr>
    </table>
   </form>
    </td>
            <td>
                <!--<label for="Solid_Option" style="float:right"><b>Merge Price</b></label>-->
                <input type="checkbox" style="float:right"name="Solid_Option" id="Solid_Option" value="yes"  onclick="showMergeOption(this)"> 
                
            </td>
        </tr>        
    </table>
</center>
<br/>
<fieldset style="background-color:white;" id="Items_Area">
        
      <legend align='center' style="padding:5px;width:30%;color:white;background-color: #006400;text-align:center "><b>ITEM LIST</b></legend>
        <center>
            <table width=100% border='1'>
            <tr>
                <td id='Search_Iframe'>
                    <div id="search_sponsor" style="width: 100%;height: 440px;overflow-x:hidden;overflow-y:scroll ">
                        <?php include 'Search_Item_list_Iframe2.php';?>
                        
                    </div>
                    
                    <!--<iframe width='100%' height=440px src='Search_Item_list_Iframe2.php?Product_Name=&Item_Category_ID=ALL'></iframe>-->
                </td>
            </tr>
            <tr>
                <td>
                    <table style="float:right">
                        <tr>
                            <td><style>
                        .eport_excel{
                            color:#FFFFFF!important;
                            height: 27px!important;
                        }
                    </style>
                                <form action="download_excel_item_price.php" method="POST">
                                    <input type="text" id="sponsor_id_txt" name="sponsor_id_txt"  style="display: none"/>
                                    <input type="text" id="Consultation_Type3" name="Consultation_Type3"  style="display: none"/>
                                    <input type="text" id="Item_Category_ID_3" name="Item_Category_ID_3" value="All"  style="display: none"/>
                                    <button type="submit" onclick="return check_general()" name="dowload_excel_btn"class='art-button eport_excel'>EXPORT TO EXCEL</button>
                                </form>
                            </td>
                            <td id="printPreview" style="text-align:right ">
                                <?php echo $separator; ?>
                            </td>
                         </tr>
                    </table>
                </td>
            </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>


<script type="text/javascript">
   
    function Include_Non_Solid(state){
      var Controler = '';
        if(state.checked){
            Controler = "checked";
        }else{
          Controler = "not checked";
        }
        if (window.XMLHttpRequest) {
          myObjectInclude = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
          myObjectInclude = new ActiveXObject('Micrsoft.XMLHTTP');
          myObjectInclude.overrideMimeType('text/xml');
        }

        myObjectInclude.onreadystatechange = function () {
          dataSolid = myObjectInclude.responseText;
          if (myObjectInclude.readyState == 4) {
            Refresh_Items();
          }
        }; //specify name of function that will handle server response........

        myObjectInclude.open('GET', 'Include_Non_Solid.php?Controler='+Controler, true);
        myObjectInclude.send();
    }
    function showMergeOption(state){
        
         if(state.checked){
            $("#merge_price_tbl").show();
            $("#item_price_filter_option").hide();
        } else{
            $("#merge_price_tbl").hide();
            $("#item_price_filter_option").show();
        }
    }
</script>

<script type="text/javascript">
  function Refresh_Items(){
    document.getElementById('itemClr').value = '';
    document.getElementById("Sponsor_Name").value = 'All';
    document.getElementById("Item_Category_ID").value = 'All';
    document.getElementById("Consultation_Type").value = 'All';

    var category_ID = document.getElementById('Item_Category_ID').value;
    var Sponsor_ID = document.getElementById('Sponsor_Name').value;
    var Consultation_Type = document.getElementById('Consultation_Type').value;

    if (window.XMLHttpRequest) {
      myObjectRefresh = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
      myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
      myObjectRefresh.overrideMimeType('text/xml');
    }

    myObjectRefresh.onreadystatechange = function () {
      dataRefresh = myObjectRefresh.responseText;
      if (myObjectRefresh.readyState == 4) {
        document.getElementById("Items_Area").innerHTML = dataRefresh;
      }
    }; //specify name of function that will handle server response........

    myObjectRefresh.open('GET', 'Include_Non_Solid_Refresh_Items.php?category_ID='+category_ID+'&Sponsor_ID='+Sponsor_ID, true);
    myObjectRefresh.send();
  }
</script>

<!--<script src="css/jquery.js"></script>-->
<script type='text/javascript'>
  $('#Sponsor_Name,#Item_Category_ID,#Consultation_Type').change(function(){
      var sponsor_id=$("#Sponsor_Name").val();
      var Consultation_Type=$("#Consultation_Type").val();
      $("#sponsor_id_txt").val(sponsor_id);
      document.getElementById('itemClr').value='';
    var sponsor=$('#Sponsor_Name').val();
    var item=$('#Item_Category_ID').val();
    $("#Item_Category_ID_3").val(item);
    $("#Consultation_Type3").val(Consultation_Type);
    
  
      $.ajax({
        type:'POST', 
        url:'Search_Item_list_Iframe2.php',
        data:'action=selectItems&Sponsor='+sponsor+'&Item='+item+'&Consultation_Type='+Consultation_Type,
        cache:false,
        success:function(html){
            var data=html.split('TenganishaData&');
            $('#search_sponsor').html(data[0]);
            $('#printPreview').html(data[1]);
        }
     });
  });
  
   function check_general(){
        var Item_Category_ID=$("#Sponsor_Name").val();
        if(Item_Category_ID=="All"){
            alert("Select Sponsor To export Item Price");
           return false; 
        }else{
            return true;
        }
    }
</script>
<script type='text/javascript'>
  $('#Sponsor_Name2,#Item_Category_ID2').change(function(){ 
      document.getElementById('itemClr').value='';
    var sponsor=$('#Sponsor_Name2').val();
    var item=$('#Item_Category_ID2').val();
      $.ajax({
        type:'POST', 
        url:'Search_Item_list_Iframe2.php',
        data:'action=selectItems&Sponsor='+sponsor+'&Item='+item,
        cache:false,
        success:function(html){
            var data=html.split('TenganishaData&');
            $('#search_sponsor').html(data[0]);
            $('#printPreview').html(data[1]);
        }
     });
  });
</script>
<script>
  function getItemName(){
      var search_word = document.getElementById('itemClr').value;
      var itemCode = document.getElementById('itemCode').value;
      var category_ID=document.getElementById('Item_Category_ID').value;
      var Sponsor_ID=document.getElementById('Sponsor_Name').value;
      
      var url='Search_Item_list_Iframe2.php?action=getItems&category_ID='+category_ID+'&Sponsor_ID='+Sponsor_ID+'&search_word='+search_word+'&itemCode='+itemCode;
    if(search_word !=''){    
        if(window.XMLHttpRequest) {
             mm = new XMLHttpRequest();
        }
        else if(window.ActiveXObject){ 
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange= function (){
           var html=mm.responseText;
          if(mm.readyState==4){ 
               var data=html.split('TenganishaData&');
            $('#search_sponsor').html(data[0]);
            $('#printPreview').html(data[1]);
            //$('#search_sponsor').html(data);
          // document.getElementById('search_sponsor').innerHTML=data;
          }
        } ;//specify name of function that will handle server response....
        mm.open('GET',url,true);
        mm.send();
    }
  }

    function getItemName(){
      var search_word = document.getElementById('itemClr').value;
      var itemCode = document.getElementById('itemCode').value;
      var category_ID=document.getElementById('Item_Category_ID').value;
      var Sponsor_ID=document.getElementById('Sponsor_Name').value;
      
      var url='Search_Item_list_Iframe2.php?action=getItems&category_ID='+category_ID+'&Sponsor_ID='+Sponsor_ID+'&search_word='+search_word+'&itemCode='+itemCode;
    if(search_word !='' || itemCode !=''){    
        if(window.XMLHttpRequest) {
             mm = new XMLHttpRequest();
        }
        else if(window.ActiveXObject){ 
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange= function (){
           var html=mm.responseText;
          if(mm.readyState==4){ 
               var data=html.split('TenganishaData&');
            $('#search_sponsor').html(data[0]);
            $('#printPreview').html(data[1]);
            //$('#search_sponsor').html(data);
          // document.getElementById('search_sponsor').innerHTML=data;
          }
        } ;//specify name of function that will handle server response....
        mm.open('GET',url,true);
        mm.send();
    }
  }
</script>
<script>
  function updatePrice(Item_ID){
//      alert("Currently this function is disabled...contact system admnstrator for more information");
//      exit;
      var category_ID=document.getElementById('Item_Category_ID').value;
      var Employee_ID='<?= $Employee_ID ?>';
      var Sponsor_ID=document.getElementById('Sponsor_Name').value;
      var ItemVal=document.getElementById('Item_'+Item_ID).value;
      var Fast_Track_Price = document.getElementById('F_Item_'+Item_ID).value;
      var url='requests/UpdateMultprice.php?action=Update&ItemID='+Item_ID+'&Sponsor='+Sponsor_ID+'&Item_Category_ID='+category_ID+'&ItemVal='+ItemVal+'&Fast_Track_Price='+Fast_Track_Price+'&Employee_ID='+Employee_ID;
     if(window.XMLHttpRequest) {
             mm = new XMLHttpRequest();
        }
        else if(window.ActiveXObject){ 
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange= function (){
           var data=mm.responseText;
           
          if(mm.readyState==4){ 
             // alert(data);
              if(data=='success'){
               alert('Item updated successfully');
              } 
          }
        } ;//specify name of function that will handle server response....
        mm.open('GET',url,true);
        mm.send();
 }
</script> 
