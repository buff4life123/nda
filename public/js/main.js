function openWindow(url){window.open(url, "_blank")}

function getFullYear() {
    return new Date().getFullYear();
}

console.log("ola");
document.getElementById("footer-fullyear").innerHTML +=  " " + getFullYear(); 

$( document ).ready(function() {

    // console.log( "ready!" );

    //get logo from backOffice
    const brandLogo = $('#brand-logo').data('brand');
    $('#brand-logo').css({
        backgroundImage: "url(../upload/gallery/"+brandLogo+"), linear-gradient(180deg,  #1a1a1a, transparent)",
        linearGradient:"(180deg,  #1a1a1a, transparent)",
        backgroundPosition: "50% 50%, 0px 0px",
        backgroundSize: "240px, auto",
        backgroundRepeat: "no-repeat, repeat"
      })
      
    $('.logo-footer').css({
        background: "url(../upload/gallery/"+brandLogo+")",
        backgroundPosition: "0px 50%",
        backgroundSize: "contain",
        backgroundRepeat: "no-repeat"
    })
});
