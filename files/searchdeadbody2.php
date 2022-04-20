<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Name_Of_Body'])){
        $Name_Of_Body = $_GET['Name_Of_Body'];   
    }else{
        $Name_Of_Body = '';
    }
	
	 if(isset($_GET['Dead_ID'])){
        $Dead_ID = $_GET['Dead_ID'];   
    }else{
        $Dead_ID = '';
    }

	
//table for technical instruction
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">	<td width = 5%><b>SN</b></td>
				<td><b>DEAD BODY NAME</b></td>
				<td><b>CORPSE NO</b></td>
                 <td><b>GENDER</b></td>
				 <td><b>AGE</b></td>
                            </tr>';
    $select_Morgue = mysqli_query($conn,"SELECT * FROM tbl_dead_regisration 
	WHERE  Status='not saved' AND Name_Of_Body LIKE'%$Name_Of_Body%' ") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_Morgue)){
        echo "<tr><td>".$temp."</td><td><a href='searchdeadbody3.php?Dead_ID=".$row['Dead_ID']."&Morgue=MorgueThisForm' target='_parent' style='text-decoration: none;'>".$row['Name_Of_Body']."</a></td>";
		echo "<td><a href='searchdeadbody3.php?Dead_ID=".$row['Dead_ID']."&Morgue=MorgueThisForm' target='_parent' style='text-decoration: none;'>".$row['Dead_ID']."</a></td>";
		
        echo "<td><a href='searchdeadbody3.php?Dead_ID=".$row['Dead_ID']."</a></td>";
        
        echo "<td><a href='searchdeadbody3.php?Dead_ID=".$row['Dead_ID']."&Morgue=MorgueThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='searchdeadbody3.php?Dead_ID=".$row['Dead_ID']."&Morgue=MorgueThisForm' target='_parent' style='text-decoration: none;'>".$row['Age']."</a></td>";
    $temp++;
    }   echo "</tr>";
?></table></center>