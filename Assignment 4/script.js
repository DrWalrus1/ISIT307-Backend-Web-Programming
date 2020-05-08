function clearForm(){
    //Hi Mark, No matter which method I used to clear values on the form on admin.php site, it wouldn't work.
    var elems = document.getElementsByClassName("clearable");
    for (var i = 0; i < elems.length; i++) {
        console.log("hello");
        elems[i].value = '';
    }

    // let form = document.getElementById("gameForm");
    // console.log("hello");
    // document.getElementById("gameTitle").value = "";
    // [...form.elements].forEach((input) => {
    //     if (input.value != null) {
    //         console.log(input.id);
    //         document.getElementById(input.id).value = "";
    //     }
    // });

}


/* OLD CODE WITH FUTURE USE
function buttonClick(e) {
    if (!e) e = window.event;
    e.stopPropagation();
    // do what you want
 }

 function ExpressInterest(plateNumber) {
    document.getElementById("searchbar").value = plateNumber;
    document.getElementById("buyForm").submit();

 }

 function showDetails (cardID) {
    let detailDiv = document.getElementById("details" + cardID);
    if (detailDiv.style.display === "none") {
        detailDiv.style.display = "block";
    } else {
        detailDiv.style.display = "none";
    }
 }
*/