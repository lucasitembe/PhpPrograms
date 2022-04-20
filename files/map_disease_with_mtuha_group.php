<?php include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
};

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}
if (isset($_SESSION['userinfo'])) {
    if (strtolower($section) == 'dhis') {
        echo "<a href='opdmappingreport.php?section=DHIS&OpdMappingReport=OpdMappingReportThisForm' class='art-button'>OPD MAPPING REPORT</a>";
        echo "<a href='adddiseasegroup.php?section=DHIS&AddNewItemCategory=AddNewItemCategoryThisPage' class='art-button'>ADD DISEASE GROUP</a>";
        echo "<a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button'>EDIT DISEASE GROUP</a>";
    } else {
        echo "<a href='opdmappingreport.php?OpdMappingReport=OpdMappingReportThisForm' class='art-button'>OPD MAPPING REPORT</a>";
        echo "<a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm#?EditdiseaseMainCategory=EditMainDiseaseCategoryThisForm' class='art-button'>BACK</a>";
    }
};
echo '
';
if (isset($_SESSION['userinfo'])) {
    if (strtolower($section) == 'dhis') {
        echo "<a href='mtuha_book_report.php?DhisWork=DhisWorkThisPage' class='art-button'>BACK</a>";
    } else {
        echo "<a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm#?EditdiseaseMainCategory=EditMainDiseaseCategoryThisForm' class='art-button'>BACK</a>";
    }
};
echo '<!--<script src="js/functions.js"></script>-->
<br/><br/>

<table width="100%">
	<tr>
		<td style="text-align: center">DISEASE GROUPS</td>
		<td style="text-align: center">ASSIGNED DISEASES</td>
		<td style="text-align: center">UNASSIGNED DISEASES</td>
	</tr>
	<tr>
		<td width="33%">
			<table width="100%">
				<tr><td><input type="text" name="Search_Disease_Group_Value" id="Search_Disease_Group_Value" onclick="Search_Disease_Groups()" oninput="Search_Disease_Groups()" autocomplete=\'off\' placeholder=\'Search Disease Group\' style="text-align: center;"></td></tr>
				<tr>
					<td>
						<fieldset style=\'overflow-y: scroll; height: 370px;\' id=\'Disease_Group_Area\'>
				';
$select_disease_group = mysqli_query($conn, "SELECT * FROM tbl_mtuha_groups WHERE id BETWEEN 5 AND 98") or die(mysqli_error($conn));
$num_rows = mysqli_num_rows($select_disease_group);
if ($num_rows > 0) {
    echo '<table width="100%">';
    while ($data = mysqli_fetch_array($select_disease_group)) {;
        echo '<tr>
				<td>
					<input type=\'radio\' name=\'Disease_Group_Selection\' id=\'';
        echo $data['id'];;
        echo '\' value=\'';
        echo $data['disease_name'];;
        echo '\' onclick="Get_Disease_Group_Name(this.value,\'';
        echo $data['id'];;
        echo '\')">
								</td>
								<td>
                 <label style="font-weight:normal" for="';
        echo $data['id'];;
        echo '">';
        echo ucwords(strtolower($data['disease_name']));;
        echo '-';
        echo "<b>(" . $data['icd_10_block'] . ")</b>";;
        echo '</label>
				</td>
					</tr>';
    }
    echo "</table>";
}; ?>
</fieldset>
</td>
</tr>
</table>
</td>
<td width="33%">
    <table width="100%">
        <tr>
            <td><input type="text" name="Search_Disease" id="Search_Disease" autocomplete='off'
                    placeholder='Search Assigned Disease' style="text-align: center;"
                    oninput="Search_Disease_Groups_Assigned()"></td>
        </tr>
        <tr>
            <td style="text-align: center;"><b id='Group_Name'></b></td>
        </tr>
        <input type="hidden" id="disease_group_id_value" name="disease_group_id" value="">
        <tr>
            <td>
                <fieldset style='overflow-y: scroll; height: 340px;' id='Disease_Group_Area_Assigned'>

                </fieldset>
            </td>
        </tr>
    </table>
</td>
<td width="33%">
    <table width="100%">
        <tr>
            <td><input type="text" name="Search_Disease_Unassigned" id="Search_Disease_Unassigned" autocomplete='off'
                    placeholder='Search Unassigned Disease' style="text-align: center;"
                    oninput="Search_Disease_Groups_Unassigned()"></td>
        </tr>
        <tr>
            <td>
                <fieldset style='overflow-y: scroll; height: 370px;' id='Disease_Group_Area_Unassigned'>

                </fieldset>
            </td>
        </tr>
    </table>
</td>
</tr>
</table>

<script type="text/javascript">
function Get_Disease_Group_Name(disease_group_name, disease_group_id) {
    //alert(disease_group_name);
    document.getElementById("disease_group_id_value").value = disease_group_id;
    document.getElementById("Group_Name").innerHTML = disease_group_name;
    showDiseasesInGroup(disease_group_id);
    showDiseasesInGroupUnassigned(disease_group_id);
}
</script>

<script type="text/javascript">
function showDiseasesInGroup(disease_group_id) {
    if (window.XMLHttpRequest) {
        myObjectShowDiseases = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectShowDiseases = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectShowDiseases.overrideMimeType('text/xml');
    }

    myObjectShowDiseases.onreadystatechange = function() {
        var data2000 = myObjectShowDiseases.responseText;
        if (myObjectShowDiseases.readyState == 4) {
            document.getElementById('Disease_Group_Area_Assigned').innerHTML = data2000;
            //showAllDiseases();
        }
    }; //specify name of function that will handle server response....
    //myObjectShowDiseases.open('GET','showDiseasesInGroup.php?disease_group_id='+group_id+'&disease_name='+disease_name,true);
    myObjectShowDiseases.open('GET', 'show_Diseases_In_Group.php?disease_group_id=' + disease_group_id, true);
    myObjectShowDiseases.send();
}
</script>


<script type="text/javascript">
function showDiseasesInGroupUnassigned(disease_group_id) {
    document.getElementById("Search_Disease_Unassigned").value = '';
    if (window.XMLHttpRequest) {
        myobjectShowUnassigend = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myobjectShowUnassigend = new ActiveXObject('Micrsoft.XMLHTTP');
        myobjectShowUnassigend.overrideMimeType('text/xml');
    }

    myobjectShowUnassigend.onreadystatechange = function() {
        var data204 = myobjectShowUnassigend.responseText;
        if (myobjectShowUnassigend.readyState == 4) {
            document.getElementById('Disease_Group_Area_Unassigned').innerHTML = data204;
        }
    }; //specify name of function that will handle server response....
    myobjectShowUnassigend.open('GET', 'show_Diseases_In_Mtuha_Unassigned.php?disease_group_id=' + disease_group_id,
        true);
    myobjectShowUnassigend.send();
}
</script>
<script type="text/javascript">
function Remove_Disease(disease_id, disease_group_id) {
    if (window.XMLHttpRequest) {
        myObjectRemoveDisease = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectRemoveDisease = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectRemoveDisease.overrideMimeType('text/xml');
    }

    myObjectRemoveDisease.onreadystatechange = function() {
        var data20009 = myObjectRemoveDisease.responseText;
        if (myObjectRemoveDisease.readyState == 4) {
            document.getElementById('Disease_Group_Area_Unassigned').innerHTML = data20009;
            showDiseasesInGroup(disease_group_id);
        }
    }; //specify name of function that will handle server response....
    myObjectRemoveDisease.open('GET', 'Remove_Disease.php?disease_id=' + disease_id, true);
    myObjectRemoveDisease.send();
}
</script>

<script type="text/javascript">
function Add_Disease(disease_id) {
    var disease_group_id_value = document.getElementById("disease_group_id_value").value;
    var Search_Disease_Unassigned = document.getElementById("Search_Disease_Unassigned").value;
    if (window.XMLHttpRequest) {
        myObjectAddDisease = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectAddDisease = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectAddDisease.overrideMimeType('text/xml');
    }

    myObjectAddDisease.onreadystatechange = function() {
        var data20 = myObjectAddDisease.responseText;
        if (myObjectAddDisease.readyState == 4) {
            Search_Disease_Groups_Unassigned()
            //document.getElementById('Disease_Group_Area_Unassigned').innerHTML = data20;
            showDiseasesInGroup(disease_group_id_value);
        }
    }; //specify name of function that will handle server response....
    myObjectAddDisease.open('GET', 'Add_Disease.php?disease_id=' + disease_id + '&disease_group_id=' +
        disease_group_id_value + '&Search_Disease_Unassigned=' + Search_Disease_Unassigned, true);
    myObjectAddDisease.send();
}
</script>

<script type="text/javascript">
function Search_Disease_Groups() {
    var Search_Disease_Group_Value = document.getElementById("Search_Disease_Group_Value").value;
    if (window.XMLHttpRequest) {
        myObjectSearchDiseaseGroup = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectSearchDiseaseGroup = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectSearchDiseaseGroup.overrideMimeType('text/xml');
    }

    myObjectSearchDiseaseGroup.onreadystatechange = function() {
        var data2011 = myObjectSearchDiseaseGroup.responseText;
        if (myObjectSearchDiseaseGroup.readyState == 4) {
            document.getElementById('Disease_Group_Area').innerHTML = data2011;
        }
    }; //specify name of function that will handle server response....
    myObjectSearchDiseaseGroup.open('GET', 'Search_Disease_Groups.php?Search_Disease_Group_Value=' +
        Search_Disease_Group_Value, true);
    myObjectSearchDiseaseGroup.send();
}
</script>

<script type="text/javascript">
function Search_Disease_Groups_Assigned() {
    var Search_Disease = document.getElementById("Search_Disease").value;
    var disease_group_id_value = document.getElementById("disease_group_id_value").value;

    if (window.XMLHttpRequest) {
        myObjectSearchAssigned = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectSearchAssigned = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectSearchAssigned.overrideMimeType('text/xml');
    }

    myObjectSearchAssigned.onreadystatechange = function() {
        var data2011 = myObjectSearchAssigned.responseText;
        if (myObjectSearchAssigned.readyState == 4) {
            document.getElementById('Disease_Group_Area_Assigned').innerHTML = data2011;
        }
    }; //specify name of function that will handle server response....
    myObjectSearchAssigned.open('GET', 'Search_Disease_Groups_Assigned.php?Search_Disease=' + Search_Disease +
        '&disease_group_id_value=' + disease_group_id_value, true);
    myObjectSearchAssigned.send();
}
</script>


<script type="text/javascript">
function Search_Disease_Groups_Unassigned() {
    var Search_Disease_Unassigned = document.getElementById("Search_Disease_Unassigned").value;
    var disease_group_id_value = document.getElementById("disease_group_id_value").value;

    if (window.XMLHttpRequest) {
        myObjectSearchAssigned = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        myObjectSearchAssigned = new ActiveXObject('Micrsoft.XMLHTTP');
        myObjectSearchAssigned.overrideMimeType('text/xml');
    }

    myObjectSearchAssigned.onreadystatechange = function() {
        var data20111 = myObjectSearchAssigned.responseText;
        if (myObjectSearchAssigned.readyState == 4) {
            document.getElementById('Disease_Group_Area_Unassigned').innerHTML = data20111;
        }
    }; //specify name of function that will handle server response....
    myObjectSearchAssigned.open(
        'GET', 'Search_Disease_Groups_Unassigned.php?Search_Disease_Unassigned=' + Search_Disease_Unassigned +
        '&disease_group_id_value=' + disease_group_id_value, true);
    myObjectSearchAssigned.send();
}
</script>
<?php
include("./includes/footer.php"); ?>