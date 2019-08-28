"use strict";

function commas(str) {
    return str.replace(/.(?=(?:.{3})+$)/g, '$&,');
}

class Product {
    constructor(barcode, title, price,stockQty, units,category, type) {
        this.barcode = barcode;
        this.title = title;
        this.price = price;
        this.stockQty = stockQty;
        this.discount = 0;
        this.units = units;
        this.category = category;
        this.type = type;
        this.quantity = 0;
    }

    setDiscount(rate) {
        this.discount = rate;
    }
    setStock(qty) {
        this.stockQty = qty;
    }

    setQuantity(qty) {
        this.quantity = qty;
    }

    setPrice(price) {
        this.price = price;
    }
}

class Item {
    constructor(product) {
        this.product = product;
        this.quantity = 0;
        this.grossAmount = 0;
        this.discountAmount = 0;
        this.netAmount = 0;
    }

    setQuantity(qty) {
        if(this.product.type === 'buy'){
            this.quantity = qty;
            this.computeAmount();
            return;
        }else if(this.product.type === 'adjust'){
            this.product.stockQty = qty;
            return;
        }else if (this.product.stockQty >= qty) {
            this.quantity = qty;
            this.computeAmount();
            return;
        }
        // this.quantity = this.product.stockQty;
        return;
    }

    setPrice(price) {
        if (price >= 0) {
            this.product.setPrice(price);
        }
        this.computeAmount();
        return;
    }

    setDiscount(rate) {
        if (rate >= 0) {
            this.product.setDiscount(rate);
        }
        this.computeAmount();
        return;
    }

    increment() {
        if(this.product.type === 'buy'){
            this.quantity++;
        }else if(this.product.type === 'adjust'){
            this.product.stockQty++;
        }else if (this.product.stockQty > this.quantity) {
            this.quantity++;
            this.computeAmount();
        }
        return;
    }

    decrement() {
        if(this.product.type === 'adjust'){
            this.product.stockQty--;
        }else if (this.quantity > 0) {
            this.quantity--;
            this.computeAmount();
        }
        return;
    }

    computeAmount() {
        this.grossAmount = this.quantity * this.product.price;
        this.discountAmount = Math.round(this.grossAmount * this.product.discount / 100);
        this.netAmount = this.grossAmount - this.discountAmount;
    }
}

class Inventory {
    constructor() {
        this.products = {};
    }

    productExists(barcode) {
        return this.products.hasOwnProperty(barcode);
    }

    addProduct(product) {
        if (!this.productExists(product.barcode)) {
            this.products[product.barcode] = product;
        }
    }
    removeProduct(barcode) {
        if (this.productExists(barcode)) {
            delete this.products[barcode];
        }
    }

    updateStock(prodCode, qty) {
        this.products[prodCode].setStock(qty);
    }

    updateItemQuantity(prodCode, qty) {
        this.products[prodCode].setQuantity(qty);
    }

    updatePrice(prodCode, price) {
        this.products[prodCode].setPrice(price);
    }

    updateDiscount(prodCode, rate) {
        this.products[prodCode].setDiscount(rate);
    }

    fetchProducts(url) {
        return fetch(url).then((response) => {
            if (response.ok) {
                return response.json();
            }
            throw Error(response.statusText);
        }).catch((error) => {
            return Promise.reject(error.message);
        });
    }
}

class Basket {
    constructor() {
        this.invoiceNumber = Date.now();
        this.items = {};
        this.grossAmount = 0;
        this.netAmount = 0;
        this.taxAmount = 0;
        this.discount = 0;
        this.taxRate = 0;
    }

    itemExists(barcode) {
        return this.items.hasOwnProperty(barcode);
    }

    addItem(item) {
        if (this.itemExists(item.product.barcode)) {
            this.items[item.product.barcode].increment();
        } else {
            this.items[item.product.barcode] = item;
        }
        this.computeAmount();
    }

    removeItem(barcode) {
        if (this.itemExists(barcode)) {
            delete this.items[barcode];
            this.computeAmount();
        }
    }

    clearBusket() {
        this.invoiceNumber = Date.now();
        this.items = {};
        this.grossAmount = 0;
        this.netAmount = 0;
        this.taxAmount = 0;
        this.discount = 0;
        this.taxRate = 0;
    }

    setTaxRate(rate) {
        this.taxRate = rate;
    }

    computeAmount() {
        this.grossAmount = 0;
        this.netAmount = 0;
        this.taxAmount = 0;
        this.discount = 0;
        for (let barcode in this.items) {
            if (this.items[barcode].quantity === 0) {
                delete this.items[barcode];
            } else {
                this.grossAmount += this.items[barcode].grossAmount;
                this.netAmount += this.items[barcode].netAmount;
                this.discount += this.items[barcode].discountAmount;
            }
        }
        this.taxAmount = Math.round(this.netAmount * this.taxRate / 100);
        this.netAmount += this.taxAmount;
        this.netAmount -= this.discount;
    }

    updateItemQuantity(prodCode, qty) {
        this.items[prodCode].setQuantity(qty);
        this.computeAmount();
    }

    updateItemPrice(prodCode, price) {
        this.items[prodCode].setPrice(price);
        this.computeAmount();
    }

    updateItemDiscount(prodCode, rate) {
        this.items[prodCode].setDiscount(rate);
        this.computeAmount();
    }


    fetchProductByBarcode(url, barcode) {
        fetch(url + barcode).then((response) => {
            if(response.ok){
                return response.json();
            }
            throw Error(response.statusText);
        }).catch((error) => {
            return Promise.reject(error.message);
        });
    }

    fetchProducts(url) {
        return fetch(url).then((response) => {
            if (response.ok) {
                return response.json();
            }
            throw Error(response.statusText);
        }).catch((error) => {
            return Promise.reject(error.message);
        });
    }
}

