$(document).ready(function () {
    $(document).on('click', '[id^="imageDelete_"]', function () {
        sweetAlert('confirm', "Voulez-vous vraiment supprimer l'image ?", $(this).data('url'))
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
                                        sweetAlert('success', 'Image supprimé !');
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