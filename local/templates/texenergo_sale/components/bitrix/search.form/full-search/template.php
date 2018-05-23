<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init(array("jquery"));
?>
<form action="<?= $arResult["FORM_ACTION"] ?>">
    <input id="js-search-full" type="text" class="searchbar searchbar-header" name="q" value="<?= (empty($_REQUEST['q']))?"":$_REQUEST['q']?>" size="15" maxlength="250"
           autocomplete="off" placeholder="Я ищу товары по референсу, артикулу, наименованию..."/>
    <button class="searbar-button" type="submit" id="search-button-head"><i class="zmdi zmdi-search"></i></button>
    <div class="searchbox hidden j-search-complete1" id="catalog-search-value1"></div>
</form>

<script type="text/javascript">
    var searchKeypressTimer = null;
    var searchKeypressAjax = null;
    $("#js-search-full").keyup(function () {
        if (searchKeypressTimer) {
            window.clearTimeout(searchKeypressTimer);
        }

        var value = this.value;
        if (value == "") {
            $('.j-search-complete1').html($("#catalog-default-value1").html());
        } else {
            searchKeypressTimer = window.setTimeout(function () {
                if (searchKeypressAjax) {
                    searchKeypressAjax.abort();
                }
                searchKeypressAjax = $.ajax({
                    dataType: 'html',
                    url: '/search/search_full.php',
                    data: {q: value},
                    success: function (data) {
                        $('.j-search-complete1').html(data).removeClass('hidden');
                        $(document).click(function (event) {
                            if ($(event.target).closest('.j-search-complete1').length)
                                return;
                            $('.j-search-complete1').addClass('hidden');
                            event.stopPropagation();
                        });
                    }
                })
            }, 300);
        }
    });

    $('#search-button-head').on('click', function(){
        if($('#js-search-full').val() == ''){
            $('#js-search-full').focus();
            return false;
        }else{
            return true;
        }
    });
</script>