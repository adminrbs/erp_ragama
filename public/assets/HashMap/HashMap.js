class HashMap {

    #map = {};

    put(key, item) {
        if (this.#map[key]) {
            //alert('Hashmap Cannot be duplicate key : ' + key);
            return false;
        }

        this.#map[key] = item;
        return true;

    }

    get(key) {
        return this.#map[key];
    }

    length() {
        return Object.keys(this.#map).length;
    }

    remove(key) {
        delete this.#map[key];
    }

    toJsonArray() {
        return this.#map;
    }

    toArray() {
        var array = [];
        for (var i in this.#map) {
            array.push([i, this.#map[i]]);
        }
        return array;
    }
}