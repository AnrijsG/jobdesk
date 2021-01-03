export class AdvertisementReply {
    /** @type {Number} */
    advertisementReplyId = -1;
    /** @type {String|null} */
    cvDownloadUrl = null;
    /** @type {String|null} */
    coverLetter = null;
    /** @type {String|null} */
    userName = null;
    /** @type {String|null} */
    userEmail = null;

    static fromArray(obj) {
        const clonedObj = _.cloneDeep(obj);

        return Object.assign(new this, clonedObj || {});
    }
}
