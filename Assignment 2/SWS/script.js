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