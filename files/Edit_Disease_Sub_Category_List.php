<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['Sub_Category_Name'])){
		$Sub_Category_Name = mysqli_real_escape_string($conn,$_GET['Sub_Category_Name']);
	}else{
		$Sub_Category_Name = '';
	}
?>

<legend align=right><b>LIST OF DISEASE SUB CATEGORIES ~ EDIT DISEASE SUB CATEGORY</b></legend>
<center>
    <table width=100%>
        <tr><td colspan="3"><hr></td></tr>
        <tr>
            <td style='text-align: left;'><b>SN</b></td>
            <td width=40%><b>DISEASE CATEGORY NAME</b></td> 
            <td><b>DISEASE SUB CATEGORY NAME</b></td> 
        </tr>
        <tr><td colspan="3"><hr></td></tr>
    <?php
        //get all desease categories
        $select = mysqli_query($conn,"select * from tbl_disease_subcategory ds, tbl_disease_category dc where
                                ds.disease_category_ID = dc.disease_category_ID and
                                ds.subcategory_description like '%$Sub_Category_Name%' order by subcategory_description limit 200") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
    ?>
               <tr>
                    <td><?php echo ++$temp; ?>.</td>
                    <td>
                        <a href="editdiseasesubcategory.php?subcategory_ID=<?php echo $data['subcategory_ID']; ?>&EditDiseaseSubCategory=EditDiseaseSubCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['category_discreption'])); ?></a>
                    </td>
                    <td>
                        <a href="editdiseasesubcategory.php?subcategory_ID=<?php echo $data['subcategory_ID']; ?>&EditDiseaseSubCategory=EditDiseaseSubCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['subcategory_description'])); ?></a>
                    </td>
                </tr> 
    <?php
            }
        }
    ?>
    </table>
</center>