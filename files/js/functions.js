/*** By Rugemarila **/
function addDatePicker(element) {
    element.datepicker({
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        showOtherMonths: true,
        dateFormat: "yy-mm-dd"
    });
}

function numberOnly(myElement) {
    var reg = new RegExp("^\d+(\.\d{1,2})?$");
    var str = myElement.value;
    if (reg.test(str)) {
        if (!isNaN(parseInt(str))) {
            intval = parseInt(str);
        } else {
            intval = '';
        }
        myElement.value = intval;
    }
}

/***********Disease Group Mapping Functions************/
var group_id = null;
var scroll_unit = 100;

function changeGroup(grpId, grpName, grpHolder) {
    document.getElementById(grpHolder).innerHTML = '<b>' + grpName + '</b>';
    group_id = grpId;
    showDiseasesInGroup();
}

function addDiseaseToGroup(diseaseId) {
    if (group_id != null) {
        if (window.XMLHttpRequest) {
            diseaseObjct = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            diseaseObjct = new ActiveXObject('Micrsoft.XMLHTTP');
            diseaseObjct.overrideMimeType('text/xml');
        }

        diseaseObjct.onreadystatechange = function () {
            var output = diseaseObjct.responseText;
            if (diseaseObjct.readyState == 4) {
                if (output != 'added') {
                    alert(output);
                } else {
                    showDiseasesInGroup();
                }
            }
        }; //specify name of function that will handle server response....
        diseaseObjct.open('GET', 'addDiseaseToGroup.php?disease_group_id=' + group_id + '&disease_Id=' + diseaseId, true);
        diseaseObjct.send();
    } else {
        alert('Choose Group First !')
    }
}

function removeDiseaseFromGroup(disease_Id) {
    if (group_id != null) {
        if (window.XMLHttpRequest) {
            removeDiseseObj = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            removeDiseseObj = new ActiveXObject('Micrsoft.XMLHTTP');
            removeDiseseObj.overrideMimeType('text/xml');
        }

        removeDiseseObj.onreadystatechange = function () {
            if (removeDiseseObj.readyState == 4) {
                showDiseasesInGroup();
            }
        }; //specify name of function that will handle server response....
        removeDiseseObj.open('GET', 'removeDiseaseFromGroup.php?disease_group_id=' + group_id + '&disease_Id=' + disease_Id, true);
        removeDiseseObj.send();
    }
}

function showDiseasesInGroup(disease_name = ''){
    if (group_id != null) {

        if (window.XMLHttpRequest) {
            groupObjct = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            groupObjct = new ActiveXObject('Micrsoft.XMLHTTP');
            groupObjct.overrideMimeType('text/xml');
        }

        groupObjct.onreadystatechange = function () {
            var diseaseResult = groupObjct.responseText;
            if (groupObjct.readyState == 4) {
                document.getElementById('disease_in_group').innerHTML = diseaseResult;
                showAllDiseases();
            }
        }; //specify name of function that will handle server response....
        groupObjct.open('GET', 'showDiseasesInGroup.php?disease_group_id=' + group_id + '&disease_name=' + disease_name, true);
        groupObjct.send();
    } else {
        alert('Choose Group First !')
    }
}

function showAllDiseases() {
    disease_name = document.getElementById('search_disease_name').value;
    if (window.XMLHttpRequest) {
        allDiseaseObj = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        allDiseaseObj = new ActiveXObject('Micrsoft.XMLHTTP');
        allDiseaseObj.overrideMimeType('text/xml');
    }

    allDiseaseObj.onreadystatechange = function () {
        var alldiseaseResult = allDiseaseObj.responseText;
        if (allDiseaseObj.readyState == 4) {
            document.getElementById('all_disease').innerHTML = alldiseaseResult;
        }
    }; //specify name of function that will handle server response....
    allDiseaseObj.open('GET', 'showAllDiseases.php?disease_name=' + disease_name + '&limit=' + scroll_unit, true);
    allDiseaseObj.send();
}

function searchDiseaseGroup(disease_group_name) {
    if (window.XMLHttpRequest) {
        diseaseGroupSearchObj = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        diseaseGroupSearchObj = new ActiveXObject('Micrsoft.XMLHTTP');
        diseaseGroupSearchObj.overrideMimeType('text/xml');
    }

    diseaseGroupSearchObj.onreadystatechange = function () {
        var allGroups = diseaseGroupSearchObj.responseText;
        if (diseaseGroupSearchObj.readyState == 4) {
            document.getElementById('all_groups').innerHTML = allGroups;
        }
    }; //specify name of function that will handle server response....
    diseaseGroupSearchObj.open('GET', 'searchDiseaseGroup.php?disease_group_name=' + disease_group_name, true);
    diseaseGroupSearchObj.send();
}

function updateScrollUnit() {
    scroll_unit++;
    showAllDiseases();
}
function resetScrollUnit() {
    scroll_unit = 100;
}

$(document).ready(function () {
    $(".numberonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
});
/*-------------------------------------------------------*/