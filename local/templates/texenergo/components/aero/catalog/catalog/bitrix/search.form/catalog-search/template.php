<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//CJSCore::Init(array("jquery"));
?>
<ul>
    <li class="search">
        <form action="<?= $arResult["FORM_ACTION"] ?>">
            <input type="text" class="searchbar searchbar-header" name="q" value="" size="15" maxlength="50"/>
        </form>   
<? /*
        <script type="text/javascript">
            var searchKeypressTimer = null;
            var searchKeypressAjax = null;
            $("#js-search").keyup(function () {
                if (searchKeypressTimer) {
                    window.clearTimeout(searchKeypressTimer);
                }
                var value = this.value;
                if (value == "") {
                    $('.j-search-complete').html($("#catalog-default-value").html());
                } else {
                    searchKeypressTimer = window.setTimeout(function () {
                        if (searchKeypressAjax) {
                            searchKeypressAjax.abort();
                        }
                        searchKeypressAjax = $.ajax({
                            dataType: 'html',
                            url: '/search/search_catalog.php',
                            data: {q: value},
                            success: function (data) {
                                $('.j-search-complete').html(data).removeClass('hidden');
                                $(document).click(function (event) {
                                    if ($(event.target).closest('.j-search-complete').length)
                                        return;
                                    $('.j-search-complete').addClass('hidden');
                                    event.stopPropagation();
                                });
                            }
                        })
                    }, 1);
                }
            });
        </script>
*/  ?>
    </li>
</ul>