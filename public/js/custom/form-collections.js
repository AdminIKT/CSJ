function addToCollection(el) {
    var container = $(el).parent('.collection-container');
    var count = container.children('.group').length;
    var proto = container.data('prototype').replace(/__NAME__/g, count);
    count 
        ? container.children('.group').eq(count-1).after(proto)
        : $(el).before(proto);

    var callback = container.data('callback');
    if (callback) {
        var x = eval(callback);
        if (typeof x == 'function') {
            x();
        }
    }
}

function rmCollection(el) {
    $(el).closest('.group').remove();
    var callback = $(el).data('callback');
    if (callback) {
        var x = eval(callback);
        if (typeof x == 'function') {
            x();
        }
    }
}
