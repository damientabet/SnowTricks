$(document).ready(function () {
    $(document).on('click', '[id^="videoDelete_"]', function () {
        sweetAlert('confirm', "Voulez-vous vraiment supprimer la vidéo ?", $(this).data('url'))
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
                    icon: 'warning',
                    title: message,
                    dangerMode: true,
                    confirm: true,
                    buttons: [
                        'Annuler',
                        'Supprimer'
                    ]
                })
                    .then(function (e) {
                        if (e === true) {
                            $.ajax({
                                type: 'GET',
                                url: dataUrl,
                                success: function (data) {
                                    if (data === 'ok') {
                                        sweetAlert('success', 'Video supprimé !');
                                        $("#media").load(" #media");
                                    } else {
                                        sweetAlert('danger', 'Une erreur est survenue !');
                                    }
                                }
                            })
                        }
                    });
                break;
        }
    }
});