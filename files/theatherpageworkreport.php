<?php
include("./includes/connection.php");
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['dir']) && !empty($_GET['dir'])) {
        
    } elseif ($_GET['dir'] == 'Section') {
        
    } else {
        if (isset($_SESSION['userinfo']['Theater_Works'])) {
            if ($_SESSION['userinfo']['Theater_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
?>
<?php //  } else{    ?> <?php
if (isset($_GET['dir']) && !empty($_GET['dir'])) {
    if ($_GET['dir'] == 'From') {
        ?>
        <a href='theatherpageworks.php?From=doctor&<?php
        if (isset($_GET['Registration_ID'])) {
            echo "Registration_ID=" . $_GET['Registration_ID'] . "&";
        }
        ?><?php
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            BACK 
        </a>

        <?php
        $direction = 'dir=From&';
    } elseif ($_GET['dir'] == 'Section') {
        ?>
        <a href='theatherpageworks.php?Section=inpatientdoctorpage&<?php
        if (isset($_GET['Registration_ID'])) {
            echo "Registration_ID=" . $_GET['Registration_ID'] . "&";
        }
        ?><?php
        if (isset($_GET['consultation_ID'])) {
            echo "consultation_ID=" . $_GET['consultation_ID'] . "";
        }
        ?>' class='art-button-green'>
            BACK 
        </a>
        <?php
        $direction = 'dir=Section&consultation_ID=' . $_GET['consultation_ID'] . '&';
    }
} else {
    ?>
    <a href='theatherpage.php' class='art-button-green'>
        BACK
    </a>
    <?php
}
?>
<!-- new date function (Contain years, Months and days)--> 
<?php
//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = '';
}
//end
?>
<!-- end of the function -->

<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_theather = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, 
						Guarantor_Name,Gender, Date_Of_Birth
				FROM 
						tbl_patient_registration pr, tbl_sponsor sp
				WHERE 
						pr.Registration_ID='$Registration_ID' AND
						pr.sponsor_id = sp.sponsor_id  ") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_theather)) {

        $Registration_ID = $row['Registration_ID'];
        $Patient_Name = $row['Patient_Name'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];

        //AGE FUNCTION
        $Age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
        // if($Age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $Age = $diff->y . " Years, ";
        $Age .= $diff->m . " Months, ";
        $Age .= $diff->d . " Days";
    }
}


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_theathers = mysqli_query($conn,"SELECT  *
				FROM 
						tbl_patient_registration pr,tbl_post_operative po,tbl_items it
				WHERE 
						pr.Registration_ID='$Registration_ID' AND
						it.Item_ID=po.Procedure_Names AND
						
						pr.Registration_ID=po.Registration_ID ") or die(mysqli_error($conn));

    $nums = mysqli_num_rows($select_theathers);

    if ($nums > 0) {

        while ($rows = mysqli_fetch_array($select_theathers)) {

            $Registration_ID = $rows['Registration_ID'];
            $Product_Name = $rows['Product_Name'];
            $date_From = $rows['date_From'];
            $Indication = $rows['Indication'];
            $Anaesthesia = $rows['Anaesthesia'];
            $Type_Of_Incision = $rows['Type_Of_Incision'];
            $Findings = $rows['Findings'];
            $Procedures = $rows['Procedures'];
            $Skin_Structure = $rows['Skin_Structure'];
            $Closure = $rows['Closure'];
            $Drains = $rows['Drains'];
            $Post_operative_ID = $rows['Post_operative_ID'];
            $Post_Operative_Date_Time = $rows['Post_Operative_Date_Time'];
        }
    } else {

        $Registration_ID = '';
        $Product_Name = '';
        $date_From = '';
        $Indication = '';
        $Anaesthesia = '';
        $Type_Of_Incision = '';
        $Findings = '';
        $Procedures = '';
        $Skin_Structure = '';
        $Closure = '';
        $Drains = '';
        $Post_operative_ID = '';
        $Post_operative_ID = '';
        $Post_Operative_Date_Time = '';
    }
}
//insertation to database
//$Surgeon_Ids = explode(',',$_POST['Surgeon_Id']);

/* 	if(isset($_POST['submitthearteform'])){     
  $Surgeon_Ids = explode(',',$_POST['Crub_Nurse']);
  foreach($Surgeon_Ids as $emp){
  $theater_insert=mysqli_query($conn,"INSERT INTO tbl_theater_registration(Surgeon_ID)
  VALUES
  ($emp)");
  }


  } */
?>

<center>
    <?php
    //if(!empty($_POST)){
    //	$Surgeon_Ids = explode(',',$_POST['Surgeon_Id']);
    //print_r($Surgeon_Ids);
//	}
    ?>
    <form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
        <fieldset style="width:95%;margin-top:10px;">
            <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
                    <?php echo $Patient_Name . ",Patient_No-" . $Registration_ID . "," . $Guarantor_Name . "," . $Gender . "," . $Age; ?></b>
            </legend>

            <hr>
            <table width='100%'>
                <tr>
                    <td style="text-align:right;" width=13%>Type Of Procedure</td>
                    <td width=24%>
                        <input type='text' value="<?php echo$Product_Name; ?>" readonly='readonly'>
                    </td>

                    <td style="text-align:right;" width=13%>Surgeon </td>
                    <td width=50%>
                        <?php
                        $empolees = mysqli_query($conn,"select * from tbl_employee,
													tbl_theater_registration
											WHERE 
												Employee_ID=Surgeon_ID AND
												Post_operative_ID='$Post_operative_ID' AND
												
												Operative_Status='surgeon'");
                        $surgeons = '';
                        while ($rowz = mysqli_fetch_array($empolees)) {
                            $Employee_ID = $rowz['Employee_ID'];
                            $Surgeon_ID = $rowz['Surgeon_ID'];
                            $Employee_Name = $rowz['Employee_Name'];
                            $Operative_Status = $rowz['Operative_Status'];


                            if ($Operative_Status == 'surgeon') {
                                $surgeons = $surgeons . '' . $Employee_Name . ';';
                            }
                        }
                        ?>
                        <input type="text" name="Surgeon"  value='<?php echo $surgeons; ?>' readonly='readonly'>
                        <?php
                        ?>
                    </td>
                </tr>	
                <tr>

                    <td style="text-align:right;" >Date And Time</td>
                    <td><input type="text" name="date_From" value="<?php echo$date_From; ?>" readonly='readonly'></td>


                    <td style="text-align:right;"> Assistant Surgeon</td>
                    <td width=50%>
                        <?php
                        $empolees = mysqli_query($conn,"select * from tbl_employee,
													tbl_theater_registration
											WHERE 
												Employee_ID=Surgeon_ID AND
												Post_operative_ID='$Post_operative_ID' AND
												
												Operative_Status='assistant_surgeon'");
                        $surgeons = '';
                        while ($rowz = mysqli_fetch_array($empolees)) {
                            $Employee_ID = $rowz['Employee_ID'];
                            $Surgeon_ID = $rowz['Surgeon_ID'];
                            $Employee_Name = $rowz['Employee_Name'];
                            $Operative_Status = $rowz['Operative_Status'];


                            if ($Operative_Status == 'assistant_surgeon') {
                                $surgeons = $surgeons . '' . $Employee_Name . ';';
                            }
                        }
                        ?>
                        <input type="text" value='<?php echo $surgeons; ?>' readonly='readonly'>
                        <?php
                        ?>
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right;">Indication</td>
                    <td><input type="text" name="Indication" value='<?php echo $Indication; ?>' readonly='readonly' ></td>

                    <td style="text-align:right;">Scrub Nurse</td>
                    <td>
                        <?php
                        $empolees = mysqli_query($conn,"select * from tbl_employee,
													tbl_theater_registration
											WHERE 
												Employee_ID=Surgeon_ID AND
												Post_operative_ID='$Post_operative_ID' AND
												
												Operative_Status='crub_nursing'");
                        $surgeons = '';
                        while ($rowz = mysqli_fetch_array($empolees)) {
                            $Employee_ID = $rowz['Employee_ID'];
                            $Surgeon_ID = $rowz['Surgeon_ID'];
                            $Employee_Name = $rowz['Employee_Name'];
                            $Operative_Status = $rowz['Operative_Status'];


                            if ($Operative_Status == 'crub_nursing') {
                                $surgeons = $surgeons . '' . $Employee_Name . ';';
                            }
                        }
                        ?>
                        <input type="text" value='<?php echo $surgeons; ?>' readonly='readonly'>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Type Of Incision</td>
                    <td>
                        <input type="text" name="Type_Of_Incision" value='<?php echo $Type_Of_Incision; ?>' readonly='readonly' >
                    </td>

                    <td style="text-align:right;">Runner Nurse</td>
                    <td >
                        <?php
                        $empolees = mysqli_query($conn,"select * from tbl_employee,
													tbl_theater_registration
											WHERE 
												Employee_ID=Surgeon_ID AND
												Post_operative_ID='$Post_operative_ID' AND
												
												Operative_Status='runners_nurse'");
                        $surgeons = '';
                        while ($rowz = mysqli_fetch_array($empolees)) {
                            $Employee_ID = $rowz['Employee_ID'];
                            $Surgeon_ID = $rowz['Surgeon_ID'];
                            $Employee_Name = $rowz['Employee_Name'];
                            $Operative_Status = $rowz['Operative_Status'];


                            if ($Operative_Status == 'runners_nurse') {
                                $surgeons = $surgeons . '' . $Employee_Name . ';';
                            }
                        }
                        ?>
                        <input type="text" value='<?php echo $surgeons; ?>' readonly='readonly'>
                    </td>

                </tr>

                <tr>
                    <td style="text-align:right;">Anaesthesia</td>
                    <td width=20%>
                        <input type="text" name="Anaesthesia" id="Anaesthesia" value='<?php echo$Anaesthesia; ?>' readonly='readonly'>
                    </td>

                    <td style="text-align:right;">Anaesthetist</td>
                    <td>

                        <?php
                        $empolees = mysqli_query($conn,"select * from tbl_employee,
													tbl_theater_registration
											WHERE 
												Employee_ID=Surgeon_ID AND
												Post_operative_ID='$Post_operative_ID' AND
												
												Operative_Status='Anaesthesing'");
                        $surgeons = '';
                        while ($rowz = mysqli_fetch_array($empolees)) {
                            $Employee_ID = $rowz['Employee_ID'];
                            $Surgeon_ID = $rowz['Surgeon_ID'];
                            $Employee_Name = $rowz['Employee_Name'];
                            $Operative_Status = $rowz['Operative_Status'];


                            if ($Operative_Status == 'Anaesthesing') {
                                $surgeons = $surgeons . ' ' . $Employee_Name . ' ;';
                            }
                        }
                        ?>
                        <input type="text" name='Anaesthesing' value='<?php echo $surgeons; ?>' readonly='readonly'>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%">
                <tr>
                    <td style="text-align:right;">Findings</td>
                    <td>
                        <textarea name="Findings" readonly='readonly' style="resize:none;"><?php echo$Findings; ?></textarea>
                    </td>
                    <td style="text-align:right;">Procedures</td>
                    <td>
                        <textarea name="Procedures" readonly='readonly' style="resize:none;"><?php echo$Procedures; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Closure</td>
                    <td>
                        <textarea name="Closure" readonly='readonly' style="resize:none;"><?php echo$Closure; ?></textarea>
                    </td>
                    <td style="text-align:right;">Skin Structure</td>
                    <td colspan=3>
                        <textarea name="Skin_Structure" readonly='readonly' style="resize:none;"><?php echo$Skin_Structure; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Drains</td>
                    <td colspan=7>
                        <textarea name="Drains" readonly='readonly' style="resize:none;"><?php echo$Drains; ?></textarea>
                    </td>
                </tr>
            </table>
            <hr>
            <table>
                <tr>
                    <td>
                        <?php
                        $data = '';
                        $photo = "SELECT name,Product_Name FROM tbl_post_operative_attachment opa INNER JOIN tbl_post_operative po ON opa.Post_operative_ID= po.Post_operative_ID JOIN tbl_items i ON i.Item_ID = po.Procedure_Names WHERE po.Post_operative_ID='$Post_operative_ID'";
                        $result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
                        if (mysqli_num_rows($result) > 0) {
                            $list = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $list++;
                                // extract($row);

                                $image = $row['name'];
                              

                                $data.= '<h3 style="text-align: center;display:inline">';
                                  if ($image != '') {
                                    $data .= "<a href='post_operative_attachments/" . $image . "' class='fancybox' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='post_operative_attachments/" . $image . "' height='150' width='150' alt='Not Image File' /></a>";
                                }
                              //  $data.= "<img height='150' width='150' alt='' class='art-lightbox' src='post_operative_attachments/" . $image . "' onclick='SelectViewer(\"$image\")' alt='" . $image . "'>";
                                $data.= '</h3>';
                            }
                        } else {
                            $data.= "<center><b style='text-align: center;font-size: 14px;font-weight: bold;color:red'>No Radiology Images For This Patient.</b></center>";
                        }

                        echo $data;
                        ?>
                    </td>
                <tr>
            </table>
        </fieldset>
    </form>	
</center>

<script>
    function CloseImage() {
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId, inVal) {
        if (inVal == 'in') {
            var zoomVal = 10;
        } else {
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue) + zoomVal;
        document.getElementById(imgId).style.width = Sizevalue + '%';
    }


</script>

<script type="text/javascript">
    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#Patient_Picture').attr('src', e.target.result).width('30%').height('20%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML = "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>
<script>
    function SelectViewer(imgSrc) {
        parent.document.getElementById('imgViewerImg').src = imgSrc;
        parent.document.getElementById('imgViewer').style.visibility = 'visible';
    }
</script>
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox();

    });
</script>

<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />


<script src="media/js/jquery.js" type="text/javascript"></script>
<!--<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>-->
<script src="css/jquery-ui.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
