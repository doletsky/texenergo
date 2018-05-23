/**
 * Created by Admin on 16.01.2015.
 */

$(function () {

    $(document).on('click', '.add-route', function () {
        $('.route-file').click();
    });

    $(document).on('change', '.route-file', function () {
        var name = ($(this).val().match(/(?:^|\/|\\)([^\\\/]+)$/)[1]);
        $('.route-file-name').text(name);
    });

    $(document).on('click', '.add-phone', function () {
        var $parent = $(this).parents('.block-source-b');
        $('.phone-template', $parent).clone().val('').removeClass('phone-template').removeAttr('required').appendTo($('.contact-phone-list', $parent));
        $('[data-phone-mask]').mask('8(999)999-99-99');
        return false;
    });

    $(document).on('click', '.select-address', function () {
        ymaps.ready(function () {
            var myMap;

            $('.select-city-map').removeClass('hidden');
            myMap = new ymaps.Map('select-city-map', {
                center: [55.753994, 37.622093],
                zoom: 9,
                behaviors: ["scrollZoom", "drag"]
            });

            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                var myGeocoder = ymaps.geocode(coords);
                myGeocoder.then(
                    function (res) {
                        $('#adress-popup').val(res.geoObjects.get(0).properties.get('text'));
                    },
                    function (err) {
                        // обработка ошибки
                    }
                );
            });
        });
    });

    $(document).on('forms.submit.success', '.deliveryForm', function(e, data){
        if(data.redirect){
            top.location.pathname = data.redirect;
        }
    });

    $(document).on('click', '.collapse', function () {
        var $this = $(this);
        if($this.data('target')){
            $($this.data('target')).slideToggle();
        }
    });

    $(document).on('change', '[name="fields[type]"]', function () {
        var $this = $(this);
        if($this.data('group')){
            $('.person-type-block.active')
                .removeClass('active')
                .find('[required]').removeAttr('required').end()
                .find('input, textarea').attr('disabled', 'disabled');
            $($this.data('group'))
                .addClass('active')
                .find('.need-required').attr('required', 'required').end()
                .find('[disabled]').removeAttr('disabled');
            $this.parents('form').parsley().destroy();
            $this.parents('form').parsley();
        }
    });

    $(document).on('change', '[name="fields[self_receiver]"]', function () {
        var $this = $(this),
            $popup = $this.parents('[data-fields]');
            $typeInput = $('.person-type-input', $popup);

        if($this.is(':checked')){
            $('.selfReceicerData input').removeAttr('disabled');
            $typeInput.attr('disabled', 'disabled').hide();
            $('.person-type-block')
                .removeClass('active')
                .find('[required]').removeAttr('required').end()
                .find('input, textarea').attr('disabled', 'disabled');
        }
        else {
            $typeInput.removeAttr('disabled').show();
            $('.selfReceicerData input').attr('disabled', 'disabled');
            $('.person-type-block')
                .first()
                .addClass('active')
                .find('.need-required').attr('required', 'required').end()
                .find('[disabled]').removeAttr('disabled');
        }
        $this.parents('form').parsley().destroy();
        $this.parents('form').parsley();
    });

    $(document).on('change', '[name="fields[send_documents]"]', function () {
        var $this = $(this),
            $popup = $this.parents('[data-fields]');
            $addressInput = $('.send-documents-address', $popup);

        if($this.data('xml-id') == "other"){
            $('.selfReceicerData input').removeAttr('disabled');
            $addressInput.removeClass('hidden');
        }
        else {
            $addressInput.addClass('hidden');
            $('textarea', $addressInput).val('');
        }
    });
});


