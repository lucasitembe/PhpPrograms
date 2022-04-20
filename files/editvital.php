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

<a href="otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>
<br/><br/>

<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>EDIT VITAL</b></legend>
                <?php
                echo '<table >
                         <thead>
                          <tr>
                            <th>SN</th><th>VITAL NAME</th>
                          </tr>
                         </thead>  ';

                $qry = mysqli_query($conn,"SELECT Vital,Vital_ID FROM  tbl_vital") or die(mysqli_error($conn));
                $sn = 1;

                while ($row = mysqli_fetch_array($qry)) {
                    echo '<tr>';
                    echo '<td>' . $sn++ . '</td>';
                    echo '<td><input type="text" cachedValue="' . $row['Vital'] . '" value="' . $row['Vital'] . '" id="' . $row['Vital_ID'] . '" class="vitalchanged"></td>';
                    echo '</tr>';
                }

                echo '</table>';
                ?>
            </fieldset>
        </center></td></tr></table>
</center>

<script>
    $('.vitalchanged').focusin().blur(function () {
        var id = $(this).attr('id');
        var cachedValue = $(this).attr('cachedValue');
        var currentValue = $(this).val();
        if (currentValue != '') {
            if (cachedValue != currentValue) {
                if (confirm("Do you want to save vital changes")) {
                    $.ajax({
                        type: 'POST',
                        url: 'editvitalRequest.php',
                        data: 'id=' + id + '&currentValue=' + currentValue,
                        cache: false,
                        success: function (result) {
                            if (result == '0') {
                                alert('VITAL ALREADY EXISTS! \nTRY ANOTHER NAME');
                            } else if (result == '1') {
                                alert('VITAL ADDED SUCCESSFUL');
                                window.location=window.location.href;
                            }
                        }
                    });
                }
            }
        }else{
            alert('Vital cannot be empty');
            $(this).val(cachedValue);
        }
        //alert('id='+id+' cache='+cachedValue+' current='+currentValue);
    });
</script>
<?php
include("./includes/footer.php");
?>