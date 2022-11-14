
class Sequences {

    orders = [];
    customCheck;
    customFields;
    customSelect;
    sequenceField;
    dateField;
    alertDiv;
    prev;
    next;
    constructor(orders = []) {
        this.orders = orders;
        this.customCheck    = $('#custom:checkbox');
        this.customFields   = $('#custom-fields');
        this.customSelect   = $('#previous:input');
        this.sequenceField  = $('#sequence:input');
        this.dateField      = $('#date:input');
        this.alertDiv       = $('#sequence-alert');
        this.prev           = this.previousItem();
        this.next           = this.nextItem();
    }

    displayCustom() {
        if (this.customCheck.prop('checked')) {
            this.customFields.removeClass('d-none');
            this.customFields.find(':input').each(function(i, el) {
                $(el).attr('disabled', false);
            });
        }
        else {
            this.customFields.addClass('d-none');
            this.customFields.find(':input').each(function(i, el) {
                $(el).attr('disabled', true).val(null);
            });
        }
        this.warning();
    }

    previousItem() {
        var id;
        if (id = this.customSelect.val()) {
            return this.orders[id];
        }
        return false;
    }

    nextItem() {
        var index = this.customSelect
                        .children('option:selected').index();
        if (index > 1) {
            var id = this.customSelect
                         .children('option').eq(index-1).val();
            return this.orders[id];
        }
        return false;
    }

    change() {
        this.prev = this.previousItem();
        this.next = this.nextItem();
        if (this.prev !== false) {
            this.sequenceField.val(this.prev.sequence + "-1");
            this.dateField.val(this.prev.date);
        }
        else {
            this.sequenceField.val(null);
            this.dateField.val(null);
        }
        this.warning();
    }

    warning() {
        var msg;
        if (this.prev !== false) {
            if (this.next !== false)
                msg = "Date must be between " + this.prev.date + " and " + this.next.date;
            else
                msg = "Date must be greather than " + this.prev.date;
        }
        this.alertDiv.html(msg);
    }
}

