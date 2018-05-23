//$(function () {
//
//  $('body').on('click', '.filter .sidebar-child.show-all', function (e) {
//    e.preventDefault();
//    $(this).prevAll('.collapse').slideDown(150);
//    $(this).hide();
//    return false;
//  });
//
//  $('.sidebar-header').click(function () {
//    var header = $(this),
//        items = header.parent().find('.sidebar-child');
//
//    if (header.hasClass('collapsed')) {
//      header.removeClass('collapsed');
//      items.show();
//      items.filter('.collapse').hide();
//    } else {
//      header.addClass('collapsed');
//      items.hide();
//    }
//  });
//
//  $('.min-price').bind("change keyup input click", function() {
//    if (this.value.match(/[^0-9]/g)) {
//      this.value = this.value.replace(/[^0-9]/g, '');
//    }
//  });
//
//  $('.max-price').bind("change keyup input click", function() {
//    if (this.value.match(/[^0-9]/g)) {
//      this.value = this.value.replace(/[^0-9]/g, '');
//    }
//  });
//
//  $('.filter .sidebar-child select').select2({
//    dropdownCssClass: 'dark-select'
//  });
//
//});


function JCSmartFilter(ajaxURL) {
    this.ajaxURL = ajaxURL;
    this.form = null;
    this.timer = null;
}

JCSmartFilter.prototype.keyup = function (input) {
    if (this.timer)
        clearTimeout(this.timer);
    this.timer = setTimeout(BX.delegate(function () {
        this.reload(input);
    }, this), 1000);
};

JCSmartFilter.prototype.click = function (checkbox) {
    if (this.timer)
        clearTimeout(this.timer);
    this.timer = setTimeout(BX.delegate(function () {
        this.reload(checkbox);
    }, this), 1000);
};

JCSmartFilter.prototype.reload = function (input) {
    this.position = $(input).closest('.sidebar-item').position();//BX.pos(input, true);
    this.form = BX.findParent(input, {'tag': 'form'});
    if (this.form) {
        var values = new Array;
        values[0] = {name: 'ajax', value: 'y'};
        this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': ['input', 'select']}, true));
        BX.ajax.loadJSON(
            this.ajaxURL,
            this.values2post(values),
            BX.delegate(this.postHandler, this)
        );
    }
};

JCSmartFilter.prototype.postHandler = function (result) {
    if (result.ITEMS) {
        for (var PID in result.ITEMS) {
            var arItem = result.ITEMS[PID];
            if (arItem.PROPERTY_TYPE == 'N' || arItem.PRICE) {
            }
            else if (arItem.VALUES) {
                for (var i in arItem.VALUES) {
                    var ar = arItem.VALUES[i];
                    var control = BX(ar.CONTROL_ID);
                    if (control) {
                        //control.parentNode.className = ar.DISABLED? 'lvl2 lvl2_disabled': 'lvl2';
                    }
                }
            }
        }
        var modef = BX('modef');
        var modef_num = BX('modef_num');
        if (modef && modef_num) {
            modef_num.innerHTML = result.ELEMENT_COUNT;
            var hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);

            if (result.FILTER_URL && hrefFILTER)
                hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);

            if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID) {
                BX.bind(hrefFILTER[0], 'click', function (e) {
                    var url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
                    BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
                    return BX.PreventDefault(e);
                });
            }

            if (result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID) {
                var url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
                BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
            }
            else {
                if (modef.style.display == 'none')
                    modef.style.display = 'block';
                modef.style.top = this.position.top + 'px';
            }
        }
    }
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements) {
    if (elements) {
        for (var i = 0; i < elements.length; i++) {
            var el = elements[i];
            if (el.disabled || !el.type)
                continue;

            switch (el.type.toLowerCase()) {
                case 'text':
                case 'textarea':
                case 'password':
                case 'hidden':
                    if (el.value.length){
                        if(el.value !== "от" && el.value !== "до"){
                            values[values.length] = {name: el.name, value: el.value};
                        }
                    }
                    break;
                case 'select':
                case 'select-one':
                    for (var j = 0; j < el.options.length; j++) {
                        if (el.options[j].selected && el.options[j].value.length > 0) {
                            values[values.length] = {name: el.name + '_' + el.options[j].value, value: 'Y'};
                            break;
                        }
                    }
                    break;
                case 'radio':
                    if (el.checked && el.value > 0)
                        values[values.length] = {name: el.name + '_' + el.value, value: 'Y'};
                    break;
                case 'checkbox':
                    if (el.checked)
                        values[values.length] = {name: el.name, value: el.value};
                    break;
                case 'select-multiple':
                    for (var j = 0; j < el.options.length; j++) {
                        if (el.options[j].selected)
                            values[values.length] = {name: el.name, value: el.options[j].value};
                    }
                    break;
                default:
                    break;
            }
        }
    }
};

JCSmartFilter.prototype.values2post = function (values) {
    var post = new Array;
    var current = post;
    var i = 0;
    while (i < values.length) {
        var p = values[i].name.indexOf('[');
        if (p == -1) {
            current[values[i].name] = values[i].value;
            current = post;
            i++;
        }
        else {
            var name = values[i].name.substring(0, p);
            var rest = values[i].name.substring(p + 1);
            if (!current[name])
                current[name] = new Array;

            var pp = rest.indexOf(']');
            if (pp == -1) {
                //Error - not balanced brackets
                current = post;
                i++;
            }
            else if (pp == 0) {
                //No index specified - so take the next integer
                current = current[name];
                values[i].name = '' + current.length;
            }
            else {
                //Now index name becomes and name and we go deeper into the array
                current = current[name];
                values[i].name = rest.substring(0, pp) + rest.substring(pp + 1);
            }
        }
    }
    return post;
};