jQuery(document).ready(function ($) {
    $('.kntnt-cta').each(function (i) {
        let $this = $(this);
        $.ajax({
            method: 'GET',
            url: wpApiSettings.root + 'kntnt-cta/v1/cta/' + $this.data('cta-group'),
            headers: {
                'X-WP-Nonce': wpApiSettings.nonce,
            },
            success: function (response) {
                $this.html(response.content);
            }
        });
    });
});