import {Product} from "./Product";

export class Item {
    public product: Product;
    public quantity: number;
    public grossAmount: number;
    public netAmount: number;

    constructor(product: Product, quantity: number){
        this.product = product;
        this.quantity = quantity;
        this.grossAmount = this.quantity * this.product.sellPrice;
        let discount = this.product.sellPrice * this.quantity * this.product.discount;
        this.netAmount = this.grossAmount - discount;
    }
}