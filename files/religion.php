
<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
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
 <a class="art-button-green" href="otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm"> BACK</a>     
<br/><br/>

<?php
if (isset($_POST['submit'])) {

    $religion = mysqli_real_escape_string($conn,$_POST['religion']);
    $sql = "insert into tbl_religions(Religion_Name) values('$religion')";
    //$check=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('RELIGION NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        }
    } else {
        echo "<script type='text/javascript'>
			    alert('RELIGION ADDED SUCCESSFUL');
			    </script>";
    }
}
//insert denomination
if (isset($_POST['submitDenomination'])) {

    $religion_id = mysqli_real_escape_string($conn,$_POST['religion_id']);
    $denomination = mysqli_real_escape_string($conn,$_POST['denomination']);
    $sql = "insert into tbl_denominations(Religion_ID,Denomination_Name) values('$religion_id','$denomination')";
    //$check=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('DENOMINATION NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        }
    } else {
        echo "<script type='text/javascript'>
			    alert('DENOMINATION ADDED SUCCESSFUL');
			    </script>";
    }
}
?>
                 
<br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW RELIGION</b></legend>
                <table>
                    <form action='#' method='post' name='myForm' id='myForm'> 
                        <tr>
                            <td width=40% style='text-align: right;'><b>RELIGION </b></td>
                            <td width=60%>
                                <input type='text' name='religion' required='required' size=70 id='religion' placeholder='Enter Religion Name'>

                            </td>
                        </tr>    		

                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='submit' id='submit' value='SAVE ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
            <fieldset>
                <legend align="center" ><b>ADD NEW DENOMINATION</b></legend>
                <table>
                    <form action='#' method='post' name='myFormDe' id='myFormDe'> 
                        <tr>
                            <td width=40% style='text-align: right;'><b>RELIGION </b></td>
                            <td width=60%>
                                <select name='religion_id' id='religion_id' required="required" >
                                    <option value="" selected='selected'>--Select Religion--</option>
                                    <?php
                                    $data = mysqli_query($conn,"select * from tbl_religions");
                                    while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                        <option value='<?php echo $row['Religion_ID']; ?>'>
                                            <?php echo $row['Religion_Name']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>                      
                        <tr>
                            <td width=40% style='text-align: right;'><b>DENOMINATION </b></td>
                            <td width=60%>
                                <input type='text' name='denomination' required='required' size=70 id='denomination' placeholder='Enter Denomination Name'>

                            </td>
                        </tr>    		

                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='submitDenomination' id='submit' value='SAVE ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                            </td>
                        </tr>
                    </form></table>
            </fieldset>         
        </center></td></tr></table>
</center>


<?php
include("./includes/footer.php");
?>