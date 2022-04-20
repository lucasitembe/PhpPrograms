<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<br/><br/>
<center>
<fieldset>
    
            <legend align=center><b>DEMOGRAPHIC REPORT</b></legend>
        <center>
            <?php
		echo '<center><table width =60% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;SPONSOR NAME</b></td>
                <td style='text-align: right;' width=10%><b>MALE</b></td>
                <td style='text-align: right;' width=10%><b>FEMALE</b></td>
		<td style='text-align: right;' width=10%><b>TOTAL</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
		$currentDate=date('Y-m-d');
    //run the query to select all data from the database according to the branch id
    $query="SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                (
                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                    pr.Registration_Date < '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                ) as male,
                (
                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                    pr.Registration_Date < '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                ) as female
            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC
            ";
    
    
    
    $select_demograph = mysqli_query($conn,$query);
    $total_Male=0;
    $total_Female=0;
    $res=mysqli_num_rows($select_demograph);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($select_demograph);
	//return rows
	$sponsorID=$row['Sponsor_ID'];
	$sponsorName=$row['Guarantor_Name'];
	$male=$row['male'];
	$female=$row['female'];
        echo "<tr><td>".($i+1)."</td>";
        echo "<td><a href='patientsVisitedPreviousDaysDetails.php?sponsorID=$sponsorID&SponsorDetails=SponsorDetailsThisPage'>".$row['Guarantor_Name']."</a></td>";
	$total_Male=$total_Male + $male;
	echo "<td style='text-align:right;'>".number_format($male)."</td>";
	$total_Female=$total_Female + $female;
	echo "<td style='text-align:right;'>".number_format($female)."</td>";
	$total=$male+$female;
	echo "<td style='text-align:right;'>".number_format($total)."</td>";
    }//end for loop
    echo "<tr><td colspan=2 style='text-align: right;'><b>&nbsp;&nbsp;Total</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($total_Male)."</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($total_Female)."</b></td>";
    $total_Male_Female=$total_Male+$total_Female;
    echo "<td style='text-align: right;'><b>".number_format($total_Male_Female)."</b></td></tr>";
	    ?>
            
            <tr>
                <td>
                    <table>
                        <tr>
                <td>
                        <table>
                            <tr>
                                <td style='text-align: center;border: 0'>
                                    <a href="visitedPreviousMultipleBarChart.php">
                                        <button>
                                            <input type='submit' name='graph' id='praph' class='art-button-green' value='GRAPHS'>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        </table>
                </td>
                <td>
                    <form action="sponsorPreview.php?DemographicPage=ThisPage" method="POST">
                <table>
                    <tr>
                         <td style='text-align: center; border: 0'>
                            <input type='submit' name='preview' id='preview' class='art-button-green' value='PREVIEW ALL'>
                        </td>
                    </tr>
                </table>
            </form>
                </td>
            </tr>
                    </table>
                </td>
            </tr>
            
            </table></center>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>