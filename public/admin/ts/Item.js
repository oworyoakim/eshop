"use strict";
exports.__esModule = true;
var Item = /** @class */ (function () {
    function Item(product, quantity) {
        this.product = product;
        this.quantity = quantity;
        this.grossAmount = this.quantity * this.product.sellPrice;
        var discount = this.product.sellPrice * this.quantity * this.product.discount;
        this.netAmount = this.grossAmount - discount;
    }
    return Item;
}());
exports.Item = Item;
