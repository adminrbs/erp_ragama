class ItemSetoff {

    #primary_id = undefined;
    #item_id = undefined;
    #batch = undefined;
    #wolesale_price = 0;
    #setoff_quantity = 0;
    #cost_price = 0;
    #retail_price = 0;
    #available_quantity = 0;
    #qty = 0;
    #foc = 0;
    #id = undefined;

    getItemID() {
        return this.#item_id;
    }

    getID() {
        return this.#id;
    }

    getQty() {
        return this.#qty;
    }

    getFoc() {
        return this.#foc;
    }

    getBatchNo() {
        return this.#batch;
    }

    getWholesalePrice() {
        return this.#wolesale_price;
    }

    getSetoffQuantity() {
        return this.#setoff_quantity;
    }

    getCostPrice() {
        return this.#cost_price;
    }

    getRetailPrice() {
        return this.#retail_price;
    }

    getAvailableQuantity() {
        return this.#available_quantity;
    }

    getPrimaryID() {
        return this.#primary_id;
    }





    setPrimaryID(primary_id) {
        this.#primary_id = primary_id;
    }

    setItemID(item_id) {
        this.#item_id = item_id;
    }

    setBatchNo(batch) {
        this.#batch = batch;
    }

    setWholesalePrice(price) {
        this.#wolesale_price = price;
    }

    setSetoffQuantity(quantity) {
        this.#setoff_quantity = quantity;
    }

    setCostPrice(price) {
        this.#cost_price = price;
    }

    setRetailPrice(price) {
        this.#retail_price = price;
    }

    setAvailableQuantity(quantity) {
        this.#available_quantity = quantity;
    }

    setQty(qty) {
        this.#qty = qty;
    }

    setFoc(foc) {
        this.#foc = foc;
    }

    setID(id) {
        this.#id = id;
    }
}