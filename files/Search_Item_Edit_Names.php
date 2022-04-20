<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->

<?php
	include("./includes/connection.php");
        if(isset($_POST['action'])){
            if($_POST['action']=='SearchProduct'){
             $Doctror_Name = mysqli_real_escape_string($conn,$_POST['item']);   
             $category=  mysqli_real_escape_string($conn,$_POST['category']);      
             $Sponsor_ID=  mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);      
             if($category=='' || $category=='NULL'){
              $get_doctors = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name like '%$Doctror_Name%' AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID') order by Product_Name LIMIT 100") or die(mysqli_error($conn));
             }else{
              $get_doctors = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Item_Subcategory_ID='$category' and Product_Name like '%$Doctror_Name%' AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID') order by Product_Name LIMIT 100") or die(mysqli_error($conn));
             } 
                
            }elseif ($_POST['action']=='SearchProductCategory') {
             $Doctror_Name = mysqli_real_escape_string($conn,$_POST['item']);   
             $category=  mysqli_real_escape_string($conn,$_POST['category']); 
              if($category=='' || $category=='NULL'){
              $get_doctors = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name like '%$Doctror_Name%' AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID')order by Product_Name LIMIT 100") or die(mysqli_error($conn));
             }else{
              $get_doctors = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Item_Subcategory_ID='$category' and Product_Name like '%$Doctror_Name%' AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID') order by Product_Name LIMIT 100") or die(mysqli_error($conn));
             }      
                
                
             }
            
            
            $doctors_num = mysqli_num_rows($get_doctors);
            echo '<table cellpadding=0 cellspacing=0 style="width:100%">';
            while ($row=  mysqli_fetch_assoc($get_doctors)){
                echo "<tr>
                        <td style='color:black; border:2px solid #ccc;text-align: left;'>
				
                        <input type='radio' class='itemNum' name='selection' item='".$row['Item_ID']."' id='".$row['Item_ID']."a' value='".$row['Product_Name']."'>
				
			</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."a'>".$row['Product_Name']."</label></td></tr>
					
                     </tr>";
             }
             echo '</table>';
            
            
        }
       
?>


					    
<style>
    .itemNum:hover{
        cursor: pointer;
    }
</style>
<script>
  $('.itemNum').on('click',function(){
        var id=$(this).attr('item');
        var item=$(this).val();
        var Sponsor_ID=$('#Sponsor_ID').val();
        $('#Pro_Name').val(item);
        $('#New_ID').val(id);
        $('#AllItem').dialog('close');
        $.ajax({
        type:'POST',
        url:"requests/Sub_Item_price.php",
        data:"action=ViewItem&item="+id+"&Sponsor="+Sponsor_ID,
         success:function(html){
            $('#Edited_Price').val(html);
        }
        });  
    });
</script>