<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<?php
if($_GET['Sub_Department_ID']!=''){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID='';
}
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
                $Current_Date=date('Y-m-d');
                echo "<tr id='thead'>
			    <td style='width:5%'><b>SN</b></td>
			    <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;SPECIMEN NAME</b></td>
		     </tr>";
    //run the query to select all data from the database according to the branch id
    $select_specimen_query ="SELECT * FROM tbl_item_list_cache il,
                                    tbl_patient_cache_test_specimen pcts,
                                    tbl_laboratory_test_specimen lts,
                                    tbl_laboratory_specimen ls
                                    WHERE il.Payment_Item_Cache_List_ID=pcts.Payment_Item_Cache_List_ID
                                    AND pcts.Laboratory_Test_Specimen_ID=lts.Laboratory_Test_specimen_ID
                                    AND lts.Specimen_ID=ls.Specimen_ID
                                    AND pcts.Specimen_Status='Collected'
                                    AND pcts.Time_Taken LIKE '%$Current_Date%' GROUP BY ls.Specimen_ID
                        ";
                        $sn=1;
    $select_specimen_result=mysqli_query($conn,$select_specimen_query);
    while($row=mysqli_fetch_array($select_specimen_result)){
        $Specimen_Name=$row['Specimen_Name'];
        echo "<tr>
            <td>".$sn."</td>
            <td>".$Specimen_Name."</td>
        </tr>";
        $sn++;
    }
   
    echo "</table></center>";
	    ?>