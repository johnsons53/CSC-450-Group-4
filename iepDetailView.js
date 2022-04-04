//alert('file!');
function showHide(elm) {
    //alert(elm);
    /* var parent, currentNode;
    // Look within the same container as the show/hide button
    parent = elm.parentNode;
    //alert(parent);
    // Select the element within the parent container where class="showHide"
    currentNode = parent.querySelector('div.expandedDetails');
    //alert(currentNode);

    if (currentNode.style.display === "none") {
        //alert("blarg");
        currentNode.style.display = "block";
    } else {
        //alert("garble");
        currentNode.style.display = "none";
    } */
    var x = document.getElementById(elm);
    //alert(x);
    if (x.style.display === "none") {
        //alert ("blarg");
        x.style.display = "block";
    } else {
        //alert("garble");
        x.style.display = "none";
    }
    
}

function changeStudent(elmID) {
    // the student we want to display
    alert(elmID);
    var x = document.getElementById(elmID);
    x.style.display = "block";
    // change all other content to display none
    const mainContentBoxes = document.getElementsByClassName('mainContent');
    for (const mainContentBox of mainContentBoxes) {
        alert(mainContentBox.id);
        if (mainContentBox.id !== elmID) {
            mainContentBox.style.display = "none";
        } 
        
        //mainContentBox.style.visibility = 'hidden';
    }
    // Set element with target ID to display:block
    

}

function openStudent(id) {
    // Hide all elements wtih class="mainContent" as default
/*     var i, mainContent;
    mainContent = document.getElementsByClassName("mainContent");
    for (i = 0; i < mainContent.length; i++) {
        mainContent[i].style.display = "none";
    } */
    alert(id);
    var i;
    var mainContentBoxes = document.getElementsByClassName('mainContent');
    for (i = 0; i < mainContentBoxes.length; i++) {
        alert(mainContentBoxes[i].id);
        mainContentBoxes[i].style.display = "none";
    }
    // Show the desired main content
    document.getElementById(id).style.display = "block";


}
document.getElementById("defaultStudent").click();
