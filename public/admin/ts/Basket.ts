import {Item} from "./Item";

export class Basket {
    public invoiceNumber: number;
    public grossAmount: number;
    public netAmount: number;
    public discount: number;
    public taxRate: number;
    public taxAmount: number;
    public items: Item[];

    public constructor() {
        this.invoiceNumber = (new Date).getMilliseconds();
        this.items = [];
        this.grossAmount = 0;
        this.netAmount = 0;
        this.taxAmount = 0;
        this.discount = 0;
        this.taxRate = 0;
    }

    public addItem(item: Item) {
        this.items.push(item);
        this.computeAmount();
    }

    public computeAmount() {
        this.items.forEach((item) => {
            this.grossAmount += item.grossAmount;
            this.netAmount += item.netAmount;
            this.discount += (item.grossAmount - item.netAmount);
        });
        this.taxAmount = this.netAmount * this.taxRate;
        this.netAmount -= this.taxAmount;
    }

    public fetchProductByBarcode(url: string, barcode: number) {
        fetch(url + '/' + barcode)
            .then(response => {
                console.log(response);
                let result = response.json();

            })
            .catch(error => {
                console.error(error);
            });
    }
}