jQuery(document).ready(function ($) {
    $('.kntnt-cta').each(function (i) {
        let $this = $(this);
        $.ajax({
            method: "POST",
            url: kntnt_cta.ajaxurl,
            cache: false,
            data: {
                'action': 'kntnt_cta',
                'cta_group': $this.data('cta-group')
            }
        }).done(function (html) {
            $this.html(html);
        });
    });
});