import {EnvironmentModel} from "./environment.model";

export class UserModel {
    /** @type {Number} */
    userId = -1;
    /** @type {String} */
    name = '';
    /** @type {String} */
    email = '';
    /** @type {Boolean} */
    isActive = true;
    /** @type {Boolean} */
    isEnvironmentOwner = false;
    /** @type {EnvironmentModel} */
    environment = new EnvironmentModel;

    static fromArray(obj) {
        const clonedObj = _.cloneDeep(obj);

        clonedObj.environment = EnvironmentModel.fromArray(clonedObj.environment);

        return Object.assign(new this, clonedObj || {});
    }
}
