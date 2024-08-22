class Item {

    #map = new HashMap();

    put(key, setoff) {
        this.#map.put(key, setoff);
    }

    add(setoff) {
        this.#map.put(setoff.getPrimaryID(), setoff);
    }

    get(key) {
        return this.#map.get(key);
    }

    length() {
        return this.#map.length();
    }

    remove(key) {
        this.#map.remove(key);
    }

    toJsonArray(){
        return this.#map.toJsonArray();
    }

    toArray(){
        return this.#map.toArray();
    }



}