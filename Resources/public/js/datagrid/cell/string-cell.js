/* global define */
define(['backgrid', 'oro/datagrid/cell-formatter'],
function(Backgrid, CellFormatter) {
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
                attributes = this.model.attributes;
            for (var attribute in previousAttributes) {     // define changed attribute
                if (!previousAttributes.hasOwnProperty(attribute)) {
                    continue;
                }
                if (previousAttributes[attribute] != attributes[attribute]) {
                    var attrName = attribute;
                    break;
                }
            }
            var link = this.model.attributes.apply_attribute_value,
                attrValue = this.model.attributes[attrName],
                fullLink = link + '?attrName=' + attrName + '&attrVal=' + attrValue;
            $.get(fullLink, function (response) {
                    var messagesHolder = document.getElementsByClassName('flash-messages-holder')[0];
                    messagesHolder.innerHTML = response.successful ? "<div class='alert alert-success fade in top-messages'>" + response.message + "</div>" : "<div class='alert alert-error fade in top-messages'>" + response.message + "</div>";
                    setTimeout(function () {
                        messagesHolder.innerHTML = "";
                    }, 3000);
                }
            );
        }
    });
});
