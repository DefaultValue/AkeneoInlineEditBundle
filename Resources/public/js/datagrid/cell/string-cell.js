/* global define */
define(['backgrid', 'oro/datagrid/cell-formatter', 'oro/messenger'],
function(Backgrid, CellFormatter, messenger) {
    'use strict';

    /**
     * String column cell. Added missing behaviour.
     *
     * @export  oro/datagrid/string-cell
     * @class   oro.datagrid.StringCell
     * @extends Backgrid.StringCell
     */
    return Backgrid.StringCell.extend({
        /**
         @property {(Backgrid.CellFormatter|Object|string)}
         */
        formatter: new CellFormatter(),

        /**
         * @inheritDoc
         */
        enterEditMode: function (e) {
            if (this.column.get("editable")) {
                e.stopPropagation();
            }
            return Backgrid.StringCell.prototype.enterEditMode.apply(this, arguments);
        },

        /**
         * @inheritDoc
         */
        exitEditMode: function () {
            var changedAttributes = this.model.changed;

            for (var attrCode in changedAttributes) {
                if (!changedAttributes.hasOwnProperty(attrCode)) {
                    continue;
                }

                var link      = this.model.attributes.update_attribute_value,
                    attrValue = this.model.attributes[attrCode];

                if (!this.model.attributes[attrCode] && !this.model.previousAttributes()[attrCode]) {
                    continue;
                }

                var postCallback = function (response) {
                    var type = response.successful ? 'success' : 'warning';
                    messenger.notify(type, response.message);
                };

                $.post(link, {'code': attrCode, 'value': attrValue}, postCallback, 'json');
            }
            this.defaultExitEditMode();
        },

        defaultExitEditMode: function () { // default behavior for exit mode
            this.$el.removeClass("error");
            this.currentEditor.remove();
            this.stopListening(this.currentEditor);
            delete this.currentEditor;
            this.$el.removeClass("editor");
            this.render();
        }
    });
});
