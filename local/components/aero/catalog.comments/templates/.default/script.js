$(function () {
    $(".tr_rating .block_raiting_big .item").click(function () {
        $(".tr_rating .block_raiting_big .item").removeClass("active");
        var rating = $(this).attr("data-id");
        $("#rating-value").val(rating);
        if (rating == 1) {
            $(".tr_rating #rating-1").addClass("active");
        } else {
            for (i=1; i <= rating; i++ ) {
                $(".tr_rating #rating-" + i).addClass("active");
            }
        }
    });

    $(document).on('click', ".btn_like", function () {

            $.ajax({
                url: '/ajax/comments_like.php',
                dataType: 'json',
                type: 'post',
                data: {
                    id: $(this).data('id')
                },
                success: function(result) {

                    if (result.data != "") {

                        $("#helpful-" + result.id).text(result.data);
                        $("#like-" + result.id).addClass("active");
                        $("#like-" + result.id).attr("data-active","true");

                    }

                }

            });

    });

    $(document).on('click', ".pdall", function (e) {

        e.preventDefault();

        $("#pcdall-" + $(this).data('id')).hide();
        $("#pdall-" + $(this).data('id')).show();

        return false;

    });

    $(document).on('click', ".pdcrop", function (e) {

        e.preventDefault();

        $("#pdall-" + $(this).data('id')).hide();
        $("#pcdall-" + $(this).data('id')).show();

        return false;

    });
});