/* global define */
define(['jquery', 'backgrid', 'oro/datagrid/cell-formatter'],
function($, Backgrid, CellFormatter) {
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
            var previousAttributes = this.model.previousAttributes(),
                attributes = this.model.attributes,
                attrName = null;

            for (var attribute in previousAttributes) {     // define changed attribute
                if (!previousAttributes.hasOwnProperty(attribute)) {
                    continue;
                }

                if (previousAttributes[attribute] != attributes[attribute]) {
                    attrName = attribute;
                    break;
                }
            }
            if (attrName === null) { // prevent sending update request if nothing changed
                this.defaultExitEditMode();
                return;
            }
            
            var link = this.model.attributes.update_attribute_value,
                attrValue = this.model.attributes[attrName];

            var postCallback = function (response) {
                var messagesHolder = document.getElementsByClassName('flash-messages-holder')[0];
                messagesHolder.innerHTML = response.successful ? "<div class='alert alert-success fade in top-messages'>" + response.message + "</div>" : "<div class='alert alert-error fade in top-messages'>" + response.message + "</div>";
                setTimeout(function () {
                    messagesHolder.innerHTML = "";
                }, 3000);
            };

            $.post(link, {'attrName': attrName, 'attrVal': attrValue}, postCallback, 'json');

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
