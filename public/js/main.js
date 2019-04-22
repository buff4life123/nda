$( document ).ready(function() {
    console.log( "ready!" );

    //get logo from backOffice
    const brandLogo = $('#brand-logo').data('brand');
    $('#brand-logo').css({
        background: "url(../upload/gallery/"+brandLogo+")",
        linearGradient:"(180deg,  #1a1a1a, transparent)",
        backgroundPosition: "50% 50%, 0px 0px",
        backgroundSize: "240px, auto",
        backgroundRepeat: "no-repeat, repeat"
      })
    $('.logo-rodape').css({
        background: "url(../upload/gallery/"+brandLogo+")",
        backgroundPosition: "0px 50%",
        backgroundSize: "contain",
        backgroundRepeat: "no-repeat"
    })
    // $('.logo-e-gradiente').css({
    //     background: "url(../upload/gallery/"+brandLogo+")",
    //     linearGradient:"(180deg,  #1a1a1a, transparent)",
    //     backgroundPosition: "50% 50%, 0px 0px",
    //     backgroundSize: "240px, auto",
    //     backgroundRepeat: "no-repeat, repeat"
  
    // })
});

