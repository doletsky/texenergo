function orderGoStep(step) {
    document.order_form.CurrentStep.value = step;
    if (document.order_form.BACK) {
        document.order_form.BACK.value = 'Y';
    }
    document.order_form.submit();
}

function ChangeGenerate(val) {
    if (val)
        document.getElementById("sof_choose_login").style.display = 'none';
    else
        document.getElementById("sof_choose_login").style.display = 'block';

    try {
        document.order_reg_form.NEW_LOGIN.focus();
    } catch (e) {
    }
}