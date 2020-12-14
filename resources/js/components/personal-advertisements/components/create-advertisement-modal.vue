<template>
    <div>
        <b-modal size="xl" :visible="showModal" @hide="toggleModal" hide-footer id="createAdvertisementModal">
            <template slot="modal-header">
                Create advertisement
            </template>
            <ValidationObserver v-slot="{ invalid }">
                <ValidationProvider name="advertisementTitle" v-slot="{errors}" rules="required" >
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Job title" label-for="advertisementTitle">
                        <b-form-input id="advertisementTitle" :state="isInputEmpty(advertisementItem.title)" v-model="advertisementItem.title"></b-form-input>
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Job category" label-for="advertisementCategory">
                    <b-form-select :options="categories" v-model="advertisementItem.category" id="advertisementCategory">
                        <template #first>
                            <b-form-select-option :value="null" disabled>-- Please select a category --</b-form-select-option>
                        </template>
                    </b-form-select>
                </b-form-group>

                <ValidationProvider name="advertisementContent" v-slot="{errors}" rules="required">
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Content" label-for="advertisementContent">
                        <b-form-textarea v-model="advertisementItem.content"
                                         :state="isInputEmpty(advertisementItem.content)"
                                         rows="8"
                                         id="advertisementContent"
                        />
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <ValidationProvider name="advertisementLocation" v-slot="{errors}" rules="required">
                    <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Location" label-for="advertisementLocation">
                        <b-form-input id="advertisementLocation" :state="isInputEmpty(advertisementItem.location)" v-model="advertisementItem.location"></b-form-input>
                    </b-form-group>
                    <p class="text-danger">{{ errors[0] }}</p>
                </ValidationProvider>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Should add salary information" label-for="advertisementSalaryCheckbox">
                    <b-form-checkbox
                        v-model="advertisementItem.shouldDefineSalary"
                        id="advertisementSalaryCheckbox"
                        switch
                    ></b-form-checkbox>
                </b-form-group>

                <div v-if="advertisementItem.shouldDefineSalary">
                    <ValidationProvider name="advertisementSalaryRangeFrom" v-slot="{dirty, errors}" rules="required|max_value:@advertisementSalaryRangeTo">
                        <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Salary from (Euros)" label-for="advertisementSalaryRangeFrom">
                            <b-form-input id="advertisementSalaryRangeFrom"
                                          v-model="advertisementItem.salaryFrom"
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
                                          v-model="advertisementItem.salaryTo"
                                          type="number"
                                          :min="advertisementItem.salaryFrom"
                                          :state="dirty && !errors.length"
                            />
                        </b-form-group>
                        <p class="text-danger">{{ errors[0] }}</p>
                    </ValidationProvider>
                </div>

                <b-form-group label-cols="4" label-cols-lg="2" label-size="md" label="Expiration date" label-for="expirationDate">
                    <b-form-datepicker name="expirationDate" v-model="advertisementItem.expirationDate" class="mb-2"></b-form-datepicker>
                </b-form-group>

                <div class="float-right">
                    <b-button @click="$bvModal.hide('createAdvertisementModal')">Cancel</b-button>
                    <b-button @click="save"
                              class="btn btn-purple"
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
import Swal from 'sweetalert2';

export default {
    name: 'create-advertisement-modal',
    components: {
        ValidationProvider,
        ValidationObserver
    },
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
                const response = await this.saveAdvertisement(this.advertisementItem);
                if (response) {
                    await Swal.fire('Advertisement saved successfully');
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
            return this.advertisementItem.category !== null && this.advertisementItem.expirationDate !== '';
        },
        ...mapGetters('personalAdvertisements', {
            showModal: storeTypes.GET_SHOW_MODAL,
            advertisementItem: storeTypes.GET_CURRENT_ADVERTISEMENT,
        }),
        ...mapGetters('advertisements', {
            categories: advertisementStoreTypes.GET_CATEGORIES,
        }),
    }
}
</script>
