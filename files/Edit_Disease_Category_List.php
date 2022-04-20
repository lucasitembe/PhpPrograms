<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['Disease_Category_Name'])){
		$Disease_Category_Name = mysqli_real_escape_string($conn,$_GET['Disease_Category_Name']);
	}else{
		$Disease_Category_Name = '';
	}
?>

<legend align=right><b>LIST OF DISEASE CATEGORIES ~ EDIT DISEASE CATEGORY</b></legend>
                <center>
                    <table width=100%>
                        <tr><td colspan="3"><hr></td></tr>
                        <tr>
                            <td style='text-align: left;'><b>SN</b></td>
                            <td width=40%><b>DISEASE MAIN CATEGORY NAME</b></td> 
                            <td><b>DISEASE CATEGORY NAME</b></td> 
                        </tr>
                        <tr><td colspan="3"><hr></td></tr>
                    <?php
                        //get all desease categories
                        $select = mysqli_query($conn,"select * from tbl_disease_maincategory dm, tbl_disease_category dc where
                                                dm.main_category_id = dc.main_category_id and
                                                dc.category_discreption like '%$Disease_Category_Name%'
                                                order by maincategory_name limit 200") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                               <tr>
                                    <td><?php echo ++$temp; ?>.</td>
                                    <td>
                                        <a href="editdiseasecategory.php?disease_category_ID=<?php echo $data['disease_category_ID']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['maincategory_name'])); ?></a>
                                    </td>
                                    <td>
                                        <a href="editdiseasecategory.php?disease_category_ID=<?php echo $data['disease_category_ID']; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['category_discreption'])); ?></a>
                                    </td>
                                </tr> 
                    <?php
                            }
                        }
                    ?>
                    </table>
                </center>