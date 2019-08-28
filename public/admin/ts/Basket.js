"use strict";
exports.__esModule = true;
var Basket = /** @class */ (function () {
    function Basket() {
        this.invoiceNumber = (new Date).getMilliseconds();
        this.items = [];
        this.grossAmount = 0;
        this.netAmount = 0;
        this.taxAmount = 0;
        this.discount = 0;
        this.taxRate = 0;
    }
    Basket.prototype.addItem = function (item) {
        this.items.push(item);
        this.computeAmount();
    };
    Basket.prototype.computeAmount = function () {
        var _this = this;
        this.items.forEach(function (item) {
            _this.grossAmount += item.grossAmount;
            _this.netAmount += item.netAmount;
            _this.discount += (item.grossAmount - item.netAmount);
        });
        this.taxAmount = this.netAmount * this.taxRate;
        this.netAmount -= this.taxAmount;
    };
    Basket.prototype.fetchProductByBarcode = function (url, barcode) {
        fetch(url + '/' + barcode)
            .then(function (response) {
            console.log(response);
            var result = response.json();
        })["catch"](function (error) {
            console.error(error);
        });
    };
    return Basket;
}());
exports.Basket = Basket;
