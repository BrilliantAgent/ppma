$(function() {
    'use strict';

    var Navigation = Backbone.View.extend({

        el: '#navigation',

        events: {
            'click .show-entry-modal':    function() { ppma.View.Entry.Content.create(); return false; },
            'click .show-password-modal': function() { ppma.View.Settings.Modal.show(); return false; }
        }

    });

    ppma.View.Navigation = new Navigation();

});