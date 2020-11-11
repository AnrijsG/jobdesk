export class AdvertisementQueryItemStructure {
    title = '';
    category = '';
    limit = 0;
    offset = 0;
    withCurrentEnvironmentId = false;

    /**
     * @param {String} title
     * @param {String}category
     * @param {Number} limit
     * @param {Number} offset
     */
    constructor(title = '', category = '', limit = 0, offset = 0) {
        this.title = title;
        this.category = category;
        this.limit = limit;
        this.offset = offset;
    }
}
