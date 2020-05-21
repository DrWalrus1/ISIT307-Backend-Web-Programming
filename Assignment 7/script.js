let changedEntries = [];

function clearForm(formID){
    let form = document.getElementById(formID);

    [...form.elements].forEach((input) => {
        if (input.type == "text") {
            if (input.value != null) {
                //console.log(input.id);
                document.getElementById(input.id).value = "";
            }
        } else if (input.type == "number") {
            if (input.value != null) {
                //console.log(input.id);
                document.getElementById(input.id).value = "";
            }
        } else if (input.type == "checkbox") {
            input.checked = false;
        }
    });

}

function checkRow(rowID, isRowIncluded) {
    if (isRowIncluded) {
        let found = false;
        for (let i = 0; i < changedEntries.length-1; i++) {
            if (changedEntries[i] === rowID) {
                found = true;
                break;
            }
        }
        if (!found) {
            changedEntries.push(rowID);
        }
    } else {
        var filtered = changedEntries.filter(function(value){ return value !== rowID;});
        changedEntries = filtered;
    }
}

function getRowData(rowID) {
    let rowData = document.getElementById(rowID).children;
    let rowInfo = [];
    for (let i = 1; i < rowData.length-1; i++) {
        let innerElements = rowData[i].children;
        for (let x = 0; x < innerElements.length; x++) {
            if (innerElements[x].hasAttribute("name")) {
                rowInfo[innerElements[x].getAttribute("name")] = innerElements[x].innerHTML;
            }
        }

    }
    return rowInfo;
}

function updateRow(rowID) {
    let data = getRowData(rowID);
    let string = buildHTTPquery(data);
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "modifyData.php", true);
    xhttp.responseType = "text";
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("type=update&rowID=" + rowID + "&" + string); //TODO: add row data
    xhttp.onload = function () {
        if (xhttp.readyState === xhttp.DONE) {
            if (xhttp.status === 200) {
                console.log(xhttp.response);
            }
        }
    };

}

function deleteRow(rowID) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "modifyData.php", true);
    xhttp.responseType = "text";
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("type=delete&rowID=" + rowID);
    xhttp.onload = function () {
        if (xhttp.readyState === xhttp.DONE) {
            if (xhttp.status === 200) {
                console.log(xhttp.response);
            }
        }
    };
}

function buildHTTPquery(array) {
    let string = "";
    let keys = Object.keys(array);
    keys.forEach(element => {
        string += "data[" + element + "]=" + array[element] + "&";
    });
    string = string.substring(0, string.length - 1);
    return encodeURI(string);
}