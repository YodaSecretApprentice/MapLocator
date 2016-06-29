 $(document).ready(function() {
    $(function() {
        if ($('p.error').length > 0) {
            txt = $('p.error').text();
            $('.notif_container').noty({text: txt, type : 'error'});
        }
        if ($('p.success').length > 0) {
            txt = $('p.success').text();
            $('.notif_container').noty({text: txt, type : 'success'});
        }
        if ($('p.information').length > 0) {
            txt = $('p.information').text();
            $('.notif_container').noty({text: txt, type : 'information'});
        }
    });
});