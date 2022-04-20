<link rel="stylesheet" href="style.css" media="screen">

<script>
    function SelectViewer(imgSrc){
        parent.document.getElementById('imgViewerImg').src = imgSrc;
        parent.document.getElementById('imgViewer').style.visibility = 'visible';
    }
</script>
<?php
include("./includes/connection.php");
$temp = 1;
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
}else{
    $Registration_ID = '';
}
?>

<?php
if(isset($_POST['submit'])){
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
    }
}

//fetching data from database

echo '<center><table width ="100%"  style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-bottom:1px solid black;"">
      <td width = "15%"><b></b></td>
	  <tr>
	  <tr>
      <td style="text-align:center;"><b></b></td>
      </tr>';

$photo="select * from tbl_radiology_image where Registration_ID='$Registration_ID'";
$result=mysqli_query($conn,$photo) or die(mysqli_error($conn));
if(mysqli_num_rows($result) > 0){
    $list=0;
    while ($row=mysqli_fetch_array($result)){
        $list++;
        extract($row);

        echo '<h3 style="text-align: center;">';
        echo "<td> <img width='150' height='200px' style='border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;' alt='' class='art-lightbox' src='$Radiology_Image' onclick='SelectViewer(\"$Radiology_Image\")'></td>";
        echo '</h3>';
    }
}else{
    echo "<td><b style='text-align: center;font-size: 14px;font-weight: bold;color:red'>No Radiology Images For This Patient.</b></td>";
}
?>