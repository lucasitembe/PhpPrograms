<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
     @session_start();
    $temp = 1;
    if(isset($_GET['Recepient_Name'])){
        $Recepient_Name = $_GET['Recepient_Name'];   
    }else{
        $Recepient_Name = '';
    }
	
	 $filter='';
	 
	 if($Recepient_Name != ''){
	     $filter=" WHERE Recepient_Name like '%$Recepient_Name%' ";
	 }
	
    echo '<center><table width =100%>';
    echo '<tr id="thead">
             <td><b>SN</b></td>
              <td><b>COURSE OF INJURY</b></td>
		<td><b>DATE CREATED</b></td>
		<td><b>ADDED BY</b></td></tr>';
			   
    
       $courseinjury=  mysqli_query($conn,"SELECT * FROM tbl_hospital_course_injuries ci JOIN tbl_employee e ON ci.Employee_ID=e.Employee_ID WHERE Branch_ID='".$_SESSION['userinfo']['Branch_ID']."'") or die(mysqli_error($conn));
       
    while($row = mysqli_fetch_array($courseinjury)){
        echo "<tr><td>".$temp."</td>"
                . "<td><a href='edit_course_of_injurie.php?hosp_course_injury_ID=".$row['hosp_course_injury_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['course_injury']."</td>";
        echo "<td><a href='edit_course_of_injurie.php?hosp_course_injury_ID=".$row['hosp_course_injury_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['date_saved']."</td>";
        echo "<td><a href='edit_course_of_injurie.php?hosp_course_injury_ID=".$row['hosp_course_injury_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['Employee_Name']."</td>";
        
	$temp++;
	echo "</tr>";
    }
?></table></center>

