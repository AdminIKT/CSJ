function addToCollection(btn) {
    var container = $('.collection-container');
    var count = container.children().length;
    var proto = container.data('prototype').replace(/__NAME__/g, count);
    container.append(proto);
}

function rmCollection(btn) {
    btn.closest('div').remove();
}
