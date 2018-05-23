<?if ($_REQUEST["favorite"]) {
    $elementID = intval($_REQUEST["favorite"])?>
    <script type="text/javascript">
        $(function() {
            $(".catalog-favorite-toggle[data-id='<?=$elementID?>']").not(".active").click();
        });
    </script>
<?}