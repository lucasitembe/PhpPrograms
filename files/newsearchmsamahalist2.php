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
	    <td><b>Jina la Mgonjwa</b></td>
		<td><b>Umri</b></td>
		<td><b>Jinsia</b></td> 
		<td><b>Namba Ya Simu</b></td>
		<td><b>Anayependekeza Msamaha</b></td>
		<td><b>Balozi</b></td>
		<td><b>Region</b></td>
		<td><b>District</b></td>
		<td><b>Ward</b></td>
	   <td><b>Kiwango Cha Elimu</b></td>		
		<td><b>Kazi Ya Mlezi</b></td>
		<td><b>Prepared By</b></td>
			</tr>';
			
    $select_msamaha = mysqli_query($conn," SELECT
	
 msamaha_ID,
 Patient_Name,
 Date_Birth,
 Gender,
 Marital_Status,
 Occupation,
 Phone_Number,
 Reason_Exception,
 Name_Approval,
 Dat_Accident, 
 Name_Balozi, 
 Region, 
 District, 
 Ward,
 Education_Level,
 Work_Wife,
 Mahudhulio, 
 Idadi_Mahudhulio, 
 Emergence_Contact,
 Prepared_By
 FROM tbl_msamaha
 WHERE Patient_Name LIKE '%$Patient_Name%'") or die(mysqli_error($conn));
/// selecting deady body whoes registered in the system newsearchmsamahalist.php
		    
    while($row = mysqli_fetch_array($select_msamaha)){
        echo "<tr><td style='text-align: center;' id='thead'>".$temp."</td>";

	    echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";	 
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Birth']."</a></td>";
        echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Name_Approval']."</a></td>";
        echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Name_Balozi']."</a></td>";   
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Region']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['District']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Ward']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Education_Level']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Work_Wife']."</a></td>";
		echo "<td><a href='updatemsamaha.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Prepared_By']."</a></td>";

	$temp++;
    }   echo "</tr>";
?>
</table>
</center>