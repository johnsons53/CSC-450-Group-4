/*  iepDetailView.js - JavaScript for hide/show details of objective and goal content
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 04/05/2022
    Revised: 
*/
function showHide(elm) {

    var x = document.getElementById(elm);
    //alert(x);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    
}




