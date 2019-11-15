<link rel="stylesheet" href="js/jquery-ui-1.12.1/jquery-ui.min.css" />
<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>    <!-- minified  --> 
<script>
$(function () {
    $("#txt_gl_name").autocomplete({
        source: "account_master_data.php?Command=get_list&gl_name=" + document.getElementById('txt_gl_name').value ,
        minLength: 2,
        select: function (event, ui) {
            $("#txt_gl_code").val(ui.item.id);
            $("#txt_gl_name").val(ui.item.name);
            $("#itemPrice").focus();
            return false;
        }
    });
    $("#txt_gl_name1").autocomplete({
        source: "account_master_data.php?Command=get_list&gl_name=" + document.getElementById('txt_gl_name1').value ,
        minLength: 2,
        select: function (event, ui) {
            $("#txt_gl_code1").val(ui.item.id);
            $("#txt_gl_name1").val(ui.item.name);
            $("#itemPrice1").focus();
            return false;
        }
    });
});
</script>

