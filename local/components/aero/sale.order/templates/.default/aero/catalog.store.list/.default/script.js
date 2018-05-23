$(function () {

    var map, marker;

    $('.orderTabs').on('easytabs:after', function (e, tab, panel, settings) {
        if ($('#msc-self').is(':visible')) {

            DG.then(function () {
                if (!map) {
                    map = DG.map('store_map', {
                        "center": [55.749213509661, 37.617202740498],
                        "zoom": 10,
                        "fullscreenControl": false
                    });
                }

                $('.store-info').on('click', function (e) {
                    e.preventDefault();
                    var box = $(this);

                    $('.store-info').removeClass('active');
                    $('.store-info input:radio').attr('checked', false);

                    box.addClass('active');
                    $('input:radio', box).attr('checked', true);

                    if (marker) {
                        map.removeLayer(marker);
                        marker = null;
                    }

                    var store = $('.store-info.active'),
                        popup = store.clone(),
                        lat = store.data('lat'),
                        lon = store.data('lon'),
                        title = store.data('title');

                    $('input', popup).remove();

                    marker = DG.marker([lat, lon], {
                        label: title
                    }).bindPopup(popup.html(), {maxWidth: 500}).addTo(map);
                    map.panTo([lat, lon]);

                    return false;
                });

                $('.store-info:first').trigger('click');
            });


        }
    });


});
