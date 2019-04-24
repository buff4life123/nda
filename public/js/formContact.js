function formContact() {
    $('.w3-text-red.w3-right').addClass('w3-hide');
    $('.w3-overlay').show()
    setTimeout(function () {
        $.ajax({
            url: "{{ path('send_email') }}",
            type: "POST",
            data: $('#formContact').serialize(),
            success: function (data) {
                $('.w3-overlay').hide()
                if (data.status == 1) {
                    $('.contact_success').removeClass('w3-hide').html('{% trans %}page_detail.contacts.form.success{% endtrans %}');
                    setTimeout(function () {
                        $('.contact_success').addClass('w3-hide')
                    }, 10000);
                } else if (data.status == 0) {
                    for (var k in data.data)
                        $('.' + data.data[k]).removeClass('w3-hide').html('{% trans %}page_detail.contacts.form.required{% endtrans %}');



                } else {
                    for (var k in data.data)
                        $('.' + data.data[k]).removeClass('w3-hide').html('{% trans %}page_detail.contacts.form.invalid{% endtrans %}');



                }
            },
            error: function (data) {
                $('.w3-overlay').hide()
                $('#modal-error').removeClass('w3-hide')
            }
        })
    }, 500)
}

                