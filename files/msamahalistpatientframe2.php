<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name= $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
	    <td><b>Jina la mgojwa</b></td>
		<td><b>Tarehe ya kuzaliwa</b></td>
		<td><b>Jinsia</b></td> 
		<td><b>Nambari ya simu</b></td>
                <td><b>Aina ya Msamaha</b></td>
		<td><b>Jina la mtu anayependekeza Msamaha</b></td>
		<td><b>Jina la Balozi</b></td>
		<td><b>Region</b></td>
		<td><b>District</b></td>
		<td><b>Ward</b></td>
	   <td><b>kiwango cha elimu</b></td>		
		<td><b>Kazi ya mke/mlezi</b></td>
		<td><b>Prepared By</b></td>
			</tr>';
    $select_msamaha = mysqli_query($conn,"SELECT
        
 
 msamaha_ID,
 Patient_Name,
 Date_Of_Birth,
 Gender,
 Occupation,
 Phone_Number,
 aina_ya_msamaha,
 anayependekeza,
 jina_la_balozi, 
 Region, 
 District, 
 Ward,
 kiwango_cha_elimu,
 kazi_mke, 
 idadi_mahudhurio,
 anayependekeza,
 Employee_Name,
 te.Employee_ID
 FROM tbl_msamaha tm,tbl_patient_registration tpr,tbl_employee te
 WHERE tm.Registration_ID=tpr.Registration_ID AND te.Employee_ID=anayependekeza AND Patient_Name LIKE '%$Patient_Name%'") or die(mysqli_error($conn));
/// selecting deady body whoes registered in the system
		    
    while($row = mysqli_fetch_array($select_msamaha)){
        echo "<tr><td style='text-align: center;' id='thead'>".$temp."</td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";	 
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['aina_ya_msamaha']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['jina_la_balozi']."</a></td>";   
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Region']."</a></td>";
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['District']."</a></td>";
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Ward']."</a></td>";
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['kiwango_cha_elimu']."</a></td>";
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['kazi_mke']."</a></td>";
		echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";

	$temp++;
    }   echo "</tr>";
?>
</table>
</center>