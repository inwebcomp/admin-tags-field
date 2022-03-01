<template>
    <div class="w-full">
        <div v-for="(address, index) in field.value">
            <form-section style="margin-bottom: 0">{{ __('Адрес') }} {{ index + 1 }}</form-section>

            <div class="w-full">
                <div class="flex flex-wrap">
                    <field-wrapper size="w-1/5">
                        <form-label>{{ __('Регион') }}</form-label>
                        <div class="form__group__input-group">
                            <app-select :options="regions()"
                                        v-model="address.region"
                                        search
                                        simpleSearch
                                        withEmpty
                                        v-bind="extraAttributes"
                                        :class="errorClasses()"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper size="w-1/6">
                        <form-label>{{ __('Город') }}</form-label>
                        <div class="form__group__input-group">
                            <app-select :options="cities(index)"
                                        v-model="address.city"
                                        search
                                        simpleSearch
                                        withEmpty
                                        v-bind="extraAttributes"
                                        :class="errorClasses()"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper size="w-1/6">
                        <form-label>{{ __('Район') }}</form-label>
                        <div class="form__group__input-group">
                            <app-select :options="districts"
                                        v-model="address.district"
                                        search
                                        simpleSearch
                                        withEmpty
                                        v-bind="extraAttributes"
                                        :class="errorClasses()"/>
                        </div>
                    </field-wrapper>
                </div>
                <div class="flex flex-wrap">
                    <field-wrapper size="w-1/5">
                        <form-label>{{ __('Улица') }}</form-label>
                        <div class="form__group__input-group">
                            <search-input :options="streets(index)"
                                          v-model="address.street"
                                          autoFilter
                                          @select="setDistrict($event, index)"
                                          v-bind="extraAttributes"
                                          :class="errorClasses()"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper size="w-1/6">
                        <form-label>{{ __('Номер') }}</form-label>
                        <div class="form__group__input-group">
                            <text-input v-model="address.number"
                                        v-bind="extraAttributes"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper size="w-1/6">
                        <form-label>{{ __('Квартира') }}</form-label>
                        <div class="form__group__input-group">
                            <text-input v-model="address.flat"
                                        v-bind="extraAttributes"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper style="width: 10%">
                        <form-label>{{ __('Подъезд') }}</form-label>
                        <div class="form__group__input-group">
                            <text-input v-model="address.porch"
                                        v-bind="extraAttributes"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper style="width: 10%">
                        <form-label>{{ __('Этаж') }}</form-label>
                        <div class="form__group__input-group">
                            <text-input v-model="address.floor"
                                        v-bind="extraAttributes"/>
                        </div>
                    </field-wrapper>
                    <field-wrapper style="width: 15%">
                        <form-label>{{ __('Время') }}</form-label>
                        <div class="form__group__input-group">
                            <text-input v-model="address.time"
                                        v-bind="extraAttributes"/>
                        </div>
                    </field-wrapper>

                    <div class="flex flex-col ml-auto items-center justify-center">
                        <div v-if="field.value.length > 1" @click="remove(index)"
                             class="text-grey-dark hover:text-danger button--icon py-2 cursor-pointer">
                            <i class="far fa-trash-alt"></i>
                        </div>
                        <div @click="setDefaultAddress(index)" :title="__('Сделать главным адресом')"
                             class="text-grey-dark hover:text-blue button--icon py-2 cursor-pointer">
                            <i class="far fa-star" v-if="field.default_address != index"></i>
                            <i class="fas fa-star text-blue" v-if="field.default_address == index"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" @click="addAddress" class="w-full cursor-pointer text-center hover:bg-grey-lighter"
                style="margin-top: -5px">
            <i class="fal fa-plus" style="font-size: 0.7rem"></i>
        </button>
    </div>
</template>

<script>
    import HandlesValidationErrors from "../mixins/HandlesValidationErrors"
    import FormField from "../mixins/FormField"

    const root = 'fields/address'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        data: () => ({
            locations: [],
            districts: [],
            castArray: true,
        }),

        computed: {
            defaultAttributes() {
                return {
                    type: this.field.type || 'text',
                    min: this.field.min,
                    max: this.field.max,
                    step: this.field.step,
                    pattern: this.field.pattern,
                    placeholder: this.field.placeholder,
                }
            },

            extraAttributes() {
                const attrs = this.field.extraAttributes

                return {
                    ...this.defaultAttributes,
                    ...attrs,
                }
            },
        },

        mounted() {
            this.fetchLocations()
        },

        methods: {
            fetchLocations() {
                App.api.request({
                    url: root + '/locations',
                }).then(({data, districts}) => {
                    this.locations = data
                    this.districts = districts
                })
            },

            regions() {
                return this.locations
            },

            cities(index) {
                if (!this.field.value[index].region)
                    return []

                let locations = this.locations.find(location => location.value == this.field.value[index].region)

                if (!locations)
                    return []

                return locations.children
            },

            streets(index) {
                if (!this.field.value[index].city || !this.cities(index).length)
                    return []

                let city = this.cities(index).find(location => location.value == this.field.value[index].city)

                return city ? city.children : []
            },

            addAddress() {
                this.field.value.push({
                    region: null,
                    city: null,
                    district: null,
                    street: null,
                    number: null,
                    flat: null,
                    porch: null,
                    floor: null,
                    time: null,
                })
            },

            remove(index) {
                if (! confirm(this.__('Удалить адрес?')))
                    return

                this.field.value = this.field.value.filter((item, i) => i != index)
            },

            setDistrict(value, index) {
                let street = this.streets(index).find(street => street.value == value)

                if (street) {
                    this.field.value[index].district = street.parent_id
                }
            },

            setDefaultAddress(index) {
                App.api.request({
                    url: root + '/default-address/' + this.field.order + '/' + index,
                    method: 'POST',
                }).then(() => {
                    this.field.default_address = index

                    this.$toasted.show(
                        this.__('Адрес установлен'),
                        {type: 'success'}
                    )
                })
            },
        },
    }
</script>

