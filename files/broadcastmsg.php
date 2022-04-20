<?php
include("./includes/connection.php");
$filename = "broadcastmesage.txt";

if (isset($_GET['action']) && $_GET['action'] == 'read') {
    $content = '';
	
	if(!file_exists($filename)){
	$myfile = fopen($filename, "w") or die("Unable to open file!");
    fclose($myfile);
}
	
    $myfileread = fopen("broadcastmesage.txt", "r") or die("Unable to open file!");
// Output one character until end-of-file
    while (!feof($myfileread)) {
        $content .= fgetc($myfileread);
    }
    fclose($myfileread);

    $cont = explode('1$#hometenga', $content);
    $type = trim(explode('&#$*9yut', $cont[0])[1]);
    $msg = trim(explode('&#$*9yut', $cont[1])[1]);
    
    echo json_encode(array('type'=>$type,'msg'=>$msg));
    
    //echo $type.'$&*&tengan^$#&*'.$msg;
    exit;
}

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } if ($_SESSION['userinfo']['can_broadcast'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href="setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage" class="art-button-green">BACK</a>
<br/><br/>

<?php
if (isset($_POST['submitbroadcst'])) {
    $brod_message = mysqli_real_escape_string($conn,trim($_POST['brod_message']));
    $broad_type = mysqli_real_escape_string($conn,trim($_POST['broad_type']));



    if ($broad_type != 'closed' && empty($brod_message)) {
        echo "<script type='text/javascript'>
                           alert('PLEASE ADD A BROADCAST MESSAGE');
                           window.location='broadcastmsg.php';
                          </script>";
    } else if ($broad_type == 'closed') {
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        fwrite($myfile, '');
        fclose($myfile);
    }

    $txt = 'type&#$*9yut' . $broad_type . '1$#hometenga';
    $txt .='msg&#$*9yut' . $brod_message;


    $myfile = fopen($filename, "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);

    echo "<script type='text/javascript'>
                           alert('MESSAGE BROADCASTED SUUCESSIFULLY');
                           window.location='broadcastmsg.php';
                          </script>";
}

$content = '';
if(!file_exists($filename)){
	$myfile = fopen($filename, "w") or die("Unable to open file!");
    fclose($myfile);
}
$myfileread = fopen($filename, "r") or die("Unable to open file!");
// Output one character until end-of-file
while (!feof($myfileread)) {
    $content .= fgetc($myfileread);
}
fclose($myfileread);

$cont = explode('1$#hometenga', $content);
$type = trim(explode('&#$*9yut', $cont[0])[1]);
$msg = trim(explode('&#$*9yut', $cont[1])[1]);

$common = "";
$error = "";
$success = "";
$warning = "";

if ($type == 'common') {
    $common = "selected";
}
if ($type == 'error') {
    $error = "selected";
}
if ($type == 'success') {
    $success = "selected";
}
if ($type == 'warning') {
    $warning = "selected";
}


//echo $type.' '.$msg;
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=80%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>MANAGE BROADCAST MESSAGES</b></legend>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return confirm('Are you sure you want to broadcast this message?')">

                    <table width=80%>

                        <tr>
                            <td width=40% style='text-align: right;'><b>Broadcast message</b></td>
                            <td width=80%><textarea type='text' name='brod_message' id='brod_message' placeholder='Enter broadcast message'><?php echo $msg; ?></textarea></td>
                        </tr>  
                        <tr>
                            <td width=40% style='text-align: right;'><b>Broadcast type</b></td>
                            <td width=80%>
                                <select required name="broad_type" id='broad_type' onchange="clear_broadmsg(this.value)">
                                    <option value='closed'>Closed</option>
                                    <option value="common" <?php echo $common; ?>>Common</option>
                                    <option value="error" <?php echo $error; ?>>Error</option>
                                    <option value="success" <?php echo $success; ?>>Success</option>
                                    <option value="warning" <?php echo $warning; ?>>Warning</option>
                                </select>
                            </td>
                        </tr>  
                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='submit' id='submit' value='   BROADCAST MESSAGE   ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                <input type='hidden' name='submitbroadcst' value='true'/> 
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </center></td></tr></table>
</center>
<script>
  function clear_broadmsg(type){
      if(type=='closed'){
          $('#brod_message').val('');
      }
  }
</script>

<?php
include("./includes/footer.php");
?>