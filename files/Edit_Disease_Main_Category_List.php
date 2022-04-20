<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['Main_Category_Name'])){
		$Main_Category_Name = mysqli_real_escape_string($conn,$_GET['Main_Category_Name']);
	}else{
		$Main_Category_Name = '';
	}
?>

<legend align=right><b>LIST OF DISEASE MAIN CATEGORIES ~ EDIT DISEASE MAIN CATEGORY</b></legend>
<center>
    <table width=70%>
        <tr><td colspan="2"><hr></td></tr>
        <tr>
            <td style='text-align: right;'><b>SN</b></td>
            <td width=95%><b>&nbsp;&nbsp;&nbsp;DISEASE MAIN CATEGORY NAME</b></td> 
        </tr>
        <tr><td colspan="2"><hr></td></tr>
    <?php
        //get all desease main categories
        $select = mysqli_query($conn,"select * from tbl_disease_maincategory where maincategory_name like '%$Main_Category_Name%' order by maincategory_name limit 200") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
    ?>
               <tr>
                    <td style='text-align: right;'><?php echo ++$temp; ?>.</td>
                    <td>&nbsp;&nbsp;&nbsp;
                        <a href="editdiseasemaincategory.php?Main_Category_ID=<?php echo $data['main_category_id']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['maincategory_name'])); ?></a>
                    </td>
                </tr> 
    <?php
            }
        }
    ?>
    <tr><td colspan="2"><hr></td></tr>
    </table>
</center>