$(function () {
    $(".phone-mask").mask("+7(999) 999-99-99");

    $('.delivery-toggle').on('change', function () {
        var form = $(this).closest('form');
        if ($(this).is(':checked')) {
            $('.delivery-address', form).hide();
        } else {
            $('.delivery-address', form).show();
        }
    });
});