export class EnvironmentModel {
    static ROLE_APPLIER = 'applier';
    static ROLE_ADVERTISER = 'advertiser';
    static ROLE_ADMIN = 'admin';

    static PUBLIC_ROLES = [
        EnvironmentModel.ROLE_ADVERTISER,
        EnvironmentModel.ROLE_APPLIER,
    ];

    /** @type {Number} */
    environmentId = -1;
    /** @type {String|null} */
    registrationHash = null;
    /** @type {String} */
    role = '';
    /** @type {String} */
    companyName = '';

    static fromArray(obj) {
        const clonedObj = _.cloneDeep(obj);

        return Object.assign(new this, clonedObj || {});
    }
}
