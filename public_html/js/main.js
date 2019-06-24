function openWindow(url){window.open(url, "_blank")}


function getFullYear() {
    return new Date().getFullYear();
}
//Add current year to footer
document.getElementById("footer-fullyear").innerHTML +=  " " + getFullYear(); 



function modalGDPR(head, content){
    $('#modal-gdpr').show()
    $('#gdpr-head').html(head)
    $('#gdpr-txt').html(content)
}

function modalTermosCondicoes(head, content){
    $('#modal-termos-condicoes').show()
    $('#termos-condicoes-head').html(head)
    $('#termos-condicoes-txt').html(content)
}


// Get the modal
var modalGdpr = document.getElementById('modal-gdpr');
var termosCondicoes = document.getElementById('modal-termos-condicoes');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modalGdpr) {
        modalGdpr.style.display = "none";
    }

    if (event.target == termosCondicoes) {
        termosCondicoes.style.display = "none";
    }

}
