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




