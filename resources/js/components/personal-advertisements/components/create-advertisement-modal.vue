<template>
    <div>
        <b-modal size="xl" :visible="showModal" @hide="toggleModal" hide-footer id="createAdvertisementModal">
            <template slot="modal-header">
                Create advertisement
            </template>
            <ValidationObserver v-slot="{ invalid }">
                <ValidationProvider name="advertisementTitle" v-slot="{errors}" rules="required" >
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Job title" label-for="advertisementTitle">
                        <b-form-input id="advertisementTitle" :state="isInputEmpty(newAdvertisementItem.title)" v-model="newAdvertisementItem.title"></b-form-input>
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Job category" label-for="advertisementCategory">
                    <b-form-select :options="categories" v-model="newAdvertisementItem.category" id="advertisementCategory">
                        <template #first>
                            <b-form-select-option :value="null" disabled>-- Please select a category --</b-form-select-option>
                        </template>
                    </b-form-select>
                </b-form-group>

                <ValidationProvider name="advertisementContent" v-slot="{errors}" rules="required">
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Content" label-for="advertisementContent">
                        <b-form-textarea v-model="newAdvertisementItem.content"
                                         :state="isInputEmpty(newAdvertisementItem.content)"
                                         rows="8"
                                         id="advertisementContent"
                        />
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <ValidationProvider name="advertisementLocation" v-slot="{errors}" rules="required">
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Location" label-for="advertisementLocation">
                        <b-form-input id="advertisementLocation" :state="isInputEmpty(newAdvertisementItem.location)" v-model="newAdvertisementItem.location"></b-form-input>
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Should add salary information" label-for="advertisementSalaryCheckbox">
                    <b-form-checkbox
                        v-model="newAdvertisementItem.shouldDefineSalary"
                        id="advertisementSalaryCheckbox"
                        switch
                    ></b-form-checkbox>
                </b-form-group>

                <div v-if="newAdvertisementItem.shouldDefineSalary">
                    <ValidationProvider name="advertisementSalaryRangeFrom" v-slot="{dirty, errors}" rules="required|max_value:@advertisementSalaryRangeTo">
                        <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Salary from (Euros)" label-for="advertisementSalaryRangeFrom">
                            <b-form-input id="advertisementSalaryRangeFrom"
                                          v-model="newAdvertisementItem.salaryFrom"
                                          type="number"
                                          min="0"
                                          :state="dirty && !errors.length"
                            />
                        </b-form-group>
                        <p class="text-danger">{{ errors[0] }}</p>
                    </ValidationProvider>
                    <ValidationProvider name="advertisementSalaryRangeTo" v-slot="{dirty, errors}" rules="required|min_value:@advertisementSalaryRangeFrom">
                        <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Salary to (Euros)" label-for="advertisementSalaryRangeTo">
                            <b-form-input id="advertisementSalaryRangeTo"
                                          v-model="newAdvertisementItem.salaryTo"
                                          type="number"
                                          :min="newAdvertisementItem.salaryFrom"
                                          :state="dirty && !errors.length"
                            />
                        </b-form-group>
                        <p class="text-danger">{{ errors[0] }}</p>
                    </ValidationProvider>
                </div>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Expiration date" label-for="expirationDate">
                    <b-form-datepicker name="expirationDate" v-model="newAdvertisementItem.expirationDate" class="mb-2"></b-form-datepicker>
                </b-form-group>

                <div class="float-right">
                    <b-button @click="$bvModal.hide('createAdvertisementModal')">Cancel</b-button>
                    <b-button @click="save"
                              style="background-color: rgb(60, 2, 7) !important"
                              :disabled="invalid || !isAdditionalFormValidationPassed"
                    >
                        Save
                    </b-button>
                </div>
            </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
import * as advertisementStoreTypes from '../../advertisement-list/stores/advertisement.types';
import * as storeTypes from '../stores/personal-advertisements.types';
import {mapActions, mapGetters, mapMutations} from "vuex";
import {ValidationObserver, ValidationProvider} from "vee-validate";
import {AdvertisementCreateItemStructure} from "../../advertisement-list/structures/advertisement-create-item.structure";
import Swal from 'sweetalert2';

export default {
    name: 'create-advertisement-modal',
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data: () => ({
        newAdvertisementItem: new AdvertisementCreateItemStructure,
    }),
    methods: {
        ...mapActions('personalAdvertisements', {
            saveAdvertisement: storeTypes.ACTION_SAVE_ADVERTISEMENT,
            getPersonalAdvertisements: storeTypes.ACTION_GET_ENVIRONMENT_ADVERTISEMENTS,
        }),
        ...mapMutations('personalAdvertisements', {
            toggleModal: storeTypes.TOGGLE_CREATE_ADVERTISEMENT_MODAL,
        }),
        isInputEmpty(input) {
            return input.length > 0;
        },
        async save() {
            try {
                const response = await this.saveAdvertisement(this.newAdvertisementItem);
                if (response) {
                    await Swal.fire('Advertisement created successfully');
                    await this.getPersonalAdvertisements();
                    this.$bvModal.hide('createAdvertisementModal');

                    return;
                }

                await Swal.fire('Advertisement saving failed!');
            } catch {}
        },
    },
    computed: {
        isAdditionalFormValidationPassed() {
            return this.newAdvertisementItem.category !== null && this.newAdvertisementItem.expirationDate !== '';
        },
        ...mapGetters('personalAdvertisements', {
            showModal: storeTypes.GET_SHOW_MODAL,
        }),
        ...mapGetters('advertisements', {
            categories: advertisementStoreTypes.GET_CATEGORIES,
        }),
    }
}
</script>