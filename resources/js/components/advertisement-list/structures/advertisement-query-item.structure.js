export class AdvertisementQueryItemStructure {
    title = '';
    category = '';
    location = '';
    limit = 0;
    offset = 0;
    withCurrentEnvironmentId = false;

    /**
     * @param {String} title
     * @param {String} category
     * @param {String} location
     * @param {Number} limit
     * @param {Number} offset
     */
    constructor(title = '', category = '', location = '', limit = 0, offset = 0) {
        this.title = title;
        this.category = category;
        this.location = location;
        this.limit = limit;
        this.offset = offset;
    }
}
