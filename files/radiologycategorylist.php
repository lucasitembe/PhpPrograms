<?php
include("includes/header.php");
include("includes/connection.php");

/* * ***************************SESSION CONTROL****************************** */
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Radiology_Works'] != 'yes' && $_SESSION['userinfo']['	Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['	Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            if ($_SESSION['userinfo']['Radiology_Works'] == 'yes') {
                @session_start();
                if (!isset($_SESSION['Radiology_Supervisor'])) {
                    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Radiology&InvalidSupervisorAuthentication=yes");
                }
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
/* * *************************** SESSION ********************************** */

if (isset($_GET['PatientType'])) {
    $PatientType = $_GET['PatientType'];
} else {
    $PatientType = '';
}
?>

<a href='radiologyworkspage.php'class='art-button-green'> BACK </a>
<?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>
<br><br>

<fieldset style='margin-top:10px;'>
    <legend align="center" style="background-color:#006400; color:white"><b>RADIOLOGY ITEMS LIST</b></legend>


    <center>
        <table  class="hiv_table" style="width:98%;margin-top:5px;">
            <tr>
                <?php
                isset($_SESSION['Radiology_Sub_Dep_ID']) ? $RSI = $_SESSION['Radiology_Sub_Dep_ID'] : $RSI = '';
                //echo $_SESSION['Radiology_Sub_Dep_ID'];
                ?>
                <td style="text-align:center">
                    <?php
                    $query2 = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Radiology' AND enabled_disabled='enabled' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name ") or die(mysqli_error($conn));
                    
                    $dataSubCategory ="<option value=''>SELECT SUB DEPARTMENT</option>";

                    while ($row = mysqli_fetch_array($query2)) {
                        $dataSubCategory.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
                    }

                    ?>
                    
                    <select style='height:28px; width:40%; padding-left:8px;' id="ChangeSubDep" onChange="ChangeSubDep(this.value)">
                        <?php
                          echo $dataSubCategory;
                        ?>
                    </select>
                    
                    <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' id="searchrest" placeholder="Search Test Name"  oninput="searchtest(this.value)" /></td>

                    <script>
                        function ChangeSubDep(subId) {
                            document.getElementById('searchrest').value='';
                            $.ajax({
                                type: 'GET',
                                url: 'requests/GetSubcategoryItemList.php',
                                data: 'subId=' + subId,
                                success: function (result) {
                                    var data = result.split('#dttaot$');
                                    $("#Search_Iframe").html(data[1]);
                                    $("#totalitem").html(data[0]);

                                }, error: function (err, msg, errorThrows) {
                                    alert(err);
                                }
                            });
                        }
                    </script>
                    <script>
                        function searchtest(testname){
                            var subId = document.getElementById('ChangeSubDep').value;
                            $.ajax({
                                type: 'GET',
                                url: 'requests/GetSubcategoryItemList.php',
                                data: 'subId=' + subId+'&testname=' + testname,
                                success: function (result) {
                                    var data = result.split('#dttaot$');
                                    $("#Search_Iframe").html(data[1]);
                                    $("#totalitem").html(data[0]);

                                }, error: function (err, msg, errorThrows) {
                                    alert(err);
                                }
                            }); 
                        }
                    </script>
                    <script>
                        function manageParamenter(Item_ID, Item_Subcategory_ID, item_Name) {
                            //alert(Item_ID+"  "+Item_Subcategory_ID+" "+item_Name);
                            $("#showdata").dialog("option", "title", "Radiology Parameter ( " + item_Name + " )");
                            $("#itemIDAdd").attr("onClick", "AddToCache('" + Item_ID + "')");
                            $("#Cached").html('');
                            // var onClick="AddToCache()"datastring='action=radparameter&Item_ID='+Item_ID+'&Item_Subcategory_ID='+Item_Subcategory_ID;
                            GetItemParameters(Item_ID);
                            $("#showdata").dialog("open");


                        }
                    </script>					

                </td>

            </tr>
        </table>
    </center>

    <script>
        function ChangePatientsList(listtype) {
            var PatientListIframe = document.getElementById('PatientListIframe');
            if (listtype == 'FromDoc') {
                PatientListIframe.src = 'RadiologyPatientsList_FromDoc.php?Sub_Department_ID=<?php echo $Sub_Department_ID ?>&PatientType=<?php echo $PatientType; ?>&listtype=FromDoc';
            } else if (listtype == 'FromRec') {
                PatientListIframe.src = 'RadiologyPatientsList.php?Sub_Department_ID=<?php echo $Sub_Department_ID ?>&PatientType=<?php echo $PatientType; ?>&listtype=FromRec';
            }
        }
    </script>
    <script>
//GET ITEM PARAMETERS
        function GetItemParameters(Item_ID) {
            if (window.XMLHttpRequest) {
                gip = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                gip = new ActiveXObject('Micrsoft.XMLHTTP');
                gip.overrideMimeType('text/xml');
            }
            gip.onreadystatechange = AJAXStat;
            gip.open('GET', 'GetItemParameters.php?Item_ID=' + Item_ID, true);
            gip.send();

            function AJAXStat() {
                var respond = gip.responseText;
                document.getElementById('ItemParameters').innerHTML = respond;
            }
        }
    </script><script>
        //LIST ALL PARAMETERS//
            function RadioParameterList() {
            if (window.XMLHttpRequest) {
                show = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                show = new ActiveXObject('Micrsoft.XMLHTTP');
                show.overrideMimeType('text/xml');
            }
            show.onreadystatechange = AJAXStat;
            show.open('GET', 'CacheRadiologyParameters.php', true);
            show.send();

            function AJAXStat() {
                var respond = show.responseText;
                document.getElementById('Cached').innerHTML = respond;
            }
        }
        
        // parameter
            function RadioParameterLists() {
            if (window.XMLHttpRequest) {
                show = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                show = new ActiveXObject('Micrsoft.XMLHTTP');
                show.overrideMimeType('text/xml');
            }
            show.onreadystatechange = AJAXStat;
            show.open('GET', 'requests/editeRadiologyParameters.php', true);
            show.send();

            function AJAXStat() {
                var respond = show.responseText;
                document.getElementById('Cached').innerHTML = respond;
            }
        }
        
        
        //CACHE THE PARAMETERS//
        function AddToCache(ItemID) {
            var Item_ID = ItemID;
            var Parameter_Name = document.getElementById('Parameter_Name').value;

            //alert(Parameter_Name);exit();
            //document.getElementById('Item_ID').value;
            if (Parameter_Name == '') {
                alert("Enter parameter name");
                exit();
            }
            if (window.XMLHttpRequest) {
                add = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                add = new ActiveXObject('Micrsoft.XMLHTTP');
                add.overrideMimeType('text/xml');
            }
            add.onreadystatechange = AJAXStat;
            add.open('GET', 'CacheRadiologyParameters.php?Item_ID=' + Item_ID + '&Parameter_Name=' + Parameter_Name, true);
            add.send();

            function AJAXStat() {
                var respond = add.responseText;
                document.getElementById('Cached').innerHTML = respond;
                document.getElementById('Parameter_Name').value = '';
            }
        }
    </script><script>
        //DELETE ITEM PARAMETER
        function RemoveThisParam(Parameter_ID) {
            if(confirm("Are you sure you want to delete")){
            var ParamRow = 'param' + Parameter_ID;
            document.getElementById(ParamRow).style.display = 'none';
            if (window.XMLHttpRequest) {
                delp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                delp = new ActiveXObject('Micrsoft.XMLHTTP');
                delp.overrideMimeType('text/xml');
            }
 //alert("udom cive");
            //delp.onreadystatechange = AJAXDelP;
            delp.open('GET', 'DeleteRadiologyParameters.php?Parameter_ID=' + Parameter_ID, true);
            delp.send();

             //alert("udom cive");
            //function AJAXDelP() {
               
                   
                 //    var paramdel = delp.responseText;
                
//                alert(paramdel);
//                document.GetElementById('DelResults').innerHTML = paramdel;
            //}
        }
        }
        
    </script>				
    <script>
        //DELETE CACHE ITEM
        function RemoveThis(Parameter_ID) {
            if(confirm("Are you sure you want to delete")){
            var CachedRow = 'row' + Parameter_ID;

            document.getElementById(CachedRow).style.display = 'none';

            if (window.XMLHttpRequest) {
                del = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                del = new ActiveXObject('Micrsoft.XMLHTTP');
                del.overrideMimeType('text/xml');
            }
            //del.onreadystatechange = AJAXDel;
            del.open('GET', 'DeleteCachedRadiologyParameters.php?Parameter_ID=' + Parameter_ID + '&Delete=yes', true);
            del.send();

           // function AJAXDel() {
          //      var paramdeleted = del.responseText;
           // }
}
        }
    </script>			
    <script>
        //SAVE ALL PARAMETERS
        function SaveAll() {
            var Sav = 'yes';
            if (window.XMLHttpRequest) {
                sav = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                sav = new ActiveXObject('Micrsoft.XMLHTTP');
                sav.overrideMimeType('text/xml');
            }
            sav.onreadystatechange = AJAXSav;
            sav.open('GET', 'SaveRadiologyParameters.php?Save=' + Sav, true);
            sav.send();

            function AJAXSav() {
                var saveResponse = sav.responseText;
                document.getElementById('Cached').innerHTML = "<strong style='background-color:white; color:green;'><center> &#x02713; Parameters Saved.</center></strong>";
                //alert('Parameters Saved!');
            }
        }
    </script>	
    <center>
        <div id="showdata" style="width:100%;  overflow:hidden;display:none;">
            <div id="parameters">
                <table width=100% >
                    <tr>
                        <td width="40%">
                            <strong>Enter Parameter Name:</strong>
                        </td>
                        <td>
                            <input type='text' name='Parameter_Name' style='padding-left:12px; height:28px;' id='Parameter_Name' required='required' placeholder='Enter Parameter'>
                        </td>
                        <td>
                            <button class='art-button-green' id="itemIDAdd" onClick="AddToCache()" style="margin-left:13px !important;" >ADD</button>
                        </td>
                    </tr>
                </table>

                <div id="Cached"></div>
                <div id="DelResults"></div>
                <div id="ItemParameters"></div>
            </div>

        </div>
        <div id="showdataEdit" style="width:100%;overflow:hidden;display:none;">
            <div id="parametersEdit">
            </div>
        </div>
        <table  class="hiv_table" style="width:98%;margin-top:5px;">
            <tr>
                <td>
                    <div id='Search_Iframe' style="height:400px; overflow-y:scroll;overflow-x:hidden">

                    </div>

                </td>

            </tr>
            <tr>
                <td id="totalitem" style="font-size:18px;font-weight:bold;">
                </td>
            </tr>
        </table>
    </center>	
</fieldset>
<br/>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
                                function editThisParam(parameter_ID, parname) {
                                    var id = parameter_ID;
                                    $("#showdataEdit").dialog("option", "title", "Edit Parameter ( " + parname + " )");
                                    //var datastring='Parameter_ID='+Parameter_ID+'&edit=yes';

                                    $.ajax({
                                        type: 'GET',
                                        url: "requests/editeRadiologyParameters.php",
                                        data: 'paraID=' + id,
                                        success: function (data) {
                                            //alert(data);
                                            $("#parametersEdit").html(data);
                                        }, error: function (jqXHR, textStatus, errorThrown) {
                                            alert(errorThrown);
                                        }
                                    });

                                    $("#showdataEdit").dialog("open");
                                }

</script>
<<script>
    //Edit Cache
     function editThisParamcache(parameter_ID, parname) {
                                    var id = parameter_ID;
                                    $("#showdataEdit").dialog("option", "title", "Edit Parameter ( " + parname + " )");
                                    //var datastring='Parameter_ID='+Parameter_ID+'&edit=yes';

                                    $.ajax({
                                        type: 'GET',
                                        url: "requests/editeCacheRadiologyParameters.php",
                                        data: 'paraID=' + id,
                                        success: function (data) {
                                            //alert(data);
                                            $("#parametersEdit").html(data);
                                        }, error: function (jqXHR, textStatus, errorThrown) {
                                            alert(errorThrown);
                                        }
                                    });

                                    $("#showdataEdit").dialog("open");
                                }

</script>
<script>
    function SaveEditedTo(paraID,Item_ID) {
        //alert(paraID);
        var id = paraID;
        var Parameter_Name = document.getElementById('Parameter_Name_Edit').value;

        if (Parameter_Name == '') {
            alert('Enter Parameter Name');
            exit();
        }
        $.ajax({
            type: 'GET',
            url: "requests/editeRadiologyParameters.php",
            data: 'saveEdit=true&paraID=' + id + '&Parameter_Name=' + Parameter_Name,
            success: function (data) {
                alert('Updated successfully');
                $("#parametersEdit").html(data);
                GetItemParameters(Item_ID);
//                RadioParameterLists();

            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

        $("#showdataEdit").dialog("open");
    }

</script>

<script>
    function SaveEditedCacheTo(paraID) {
        //alert(paraID);
        var id = paraID;
        var Parameter_Name = document.getElementById('Parameter_Name_Edit').value;

        if (Parameter_Name == '') {
            alert('Enter Parameter Name');
            exit();
        }
        $.ajax({
            type: 'GET',
            url: "requests/editeCacheRadiologyParameters.php",
            data: 'saveEdit=true&paraID=' + id + '&Parameter_Name=' + Parameter_Name,
            success: function (data) {
                alert('Updated successfully');
                $("#parametersEdit").html(data);
                RadioParameterList();
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

        $("#showdataEdit").dialog("open");
    }

</script>
<script>
    $(document).ready(function () {
        $("#showdata,#showdataEdit").dialog({autoOpen: false, width: '90%', title: '', modal: true, position: 'top'});
        var firstSelected = $('#ChangeSubDep option:selected').text();
        var subId = $('#ChangeSubDep').val();
        //alert(firstSelected);
        if (firstSelected != 'SELECT SUB DEPARTMENT') {

            $.ajax({
                type: 'GET',
                url: 'requests/GetSubcategoryItemList.php',
                data: 'subId=' + subId,
                success: function (result) {
                    var data = result.split('#dttaot$');
                    $("#Search_Iframe").html(data[1]);
                    $("#totalitem").html(data[0]);

                }, error: function (err, msg, errorThrows) {
                    alert(err);
                }
            });
        }

    });
</script>
<?php
include("./includes/footer.php");
?>
   
