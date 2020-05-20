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

function checkField(priceID) {
    
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
// document.getElementById("PS4").indeterminate = true;