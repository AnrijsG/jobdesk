import {EnvironmentModel} from "../../auth/models/environment.model";

export class AdvertisementModel {
    /** @type {Number} */
    advertisementId = -1;
    /** @type {String} */
    category = '';
    /** @type {String} */
    title = '';
    /** @type {String} */
    content = '';
    /** @type {EnvironmentModel} */
    environment = new EnvironmentModel;

    static fromArray(obj) {
        const clonedObj = _.cloneDeep(obj);

        clonedObj.environment = EnvironmentModel.fromArray(clonedObj.environment);

        return Object.assign(new this, clonedObj || {});
    }
}
