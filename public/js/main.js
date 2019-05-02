function openWindow(url){window.open(url, "_blank")}


function getFullYear() {
    return new Date().getFullYear();
}
//Add current year to footer
document.getElementById("footer-fullyear").innerHTML +=  " " + getFullYear(); 



