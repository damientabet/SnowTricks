$(document).ready(function () {
    $(document).on('click', '[id^="deleteMedia_"]', function () {
        sweetAlert('confirm', "Voulez-vous vraiment supprimer l'élément ?", $(this).data('url'))
    });

    $('.delete').on('click', function () {
        return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
    });

    $('#image_name').on('change', function () {
        var filename = this.files[0].name;
        $('.custom-file-label').text(filename);
    });

    function sweetAlert(type, message, dataUrl = null) {
        switch (type) {
            case 'success' :
                swal({
                    title: message,
                    text: 'C\'est bon !',
                    icon: type,
                    button: false,
                    timer: 1000
                });
                break;
            case 'confirm' :
                swal({
                    icon: "warning",
                    title: message,
                    dangerMode: true,
                    confirm: true,
                    buttons: ['Annuler', 'Supprimer']
                }).then(function (e) {
                    if (e === true) {
                        ajaxDeleteElement(dataUrl);
                    }
                    });
                break;
        }
    }

    function ajaxDeleteElement(dataUrl) {
        $.ajax({
            type: 'GET',
            url: dataUrl,
            success: function (data) {
                if (data === 'ok') {
                    sweetAlert('success', 'Element supprimé !');
                    $("#media").load(" #media");
                } else {
                    sweetAlert('danger', 'Une erreur est survenue !');
                }
            }
        })
    }
});