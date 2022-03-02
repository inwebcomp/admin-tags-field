<template>
    <default-field :field="field" :errors="errors" :inline="inline" v-bind="other">
        <template v-slot:field>
            <div>
                <div class="flex">
<!--                    <draggable class="flex"-->
<!--                               :value="field.value"-->
<!--                               @input="change"-->
<!--                               tag="div"-->
<!--                               v-bind="dragOptions"-->
<!--                               @end="dragEnd">-->
                        <div class="bg-grey-lightest rounded flex mr-2 mb-2 items-center"
                             v-for="(tag, $i) in tags" :key="$i" :title="__('Убрать тег')">
<!--                            <div class="cursor-move py-1 px-2 select-none">-->
<!--                                <i class="icon icon&#45;&#45;handle mt-1"></i>-->
<!--                            </div>-->
                            <div class="py-1 px-2 pr-0">
                                {{ tag }}
                            </div>
                            <div class="hover:text-danger cursor-pointer py-1 px-2" @click="removeTag(tag)">
                                <i class="far fa-times text-sm"></i>
                            </div>
                        </div>
<!--                    </draggable>-->
                </div>

                <search-input
                    @search="search"
                    @enter.prevent.stop="addTag($event.target.value)"
                    @select="addTag"
                    :options="options"
                    :id="field.attribute"
                    v-model="newTag"
                    v-bind="extraAttributes"
                    :placeholder="__('Добавить тег')"
                    :class="errorClasses()">
                </search-input>
            </div>
        </template>
    </default-field>
</template>

<script>
import HandlesValidationErrors from "../mixins/HandlesValidationErrors"
import FormField from "../mixins/FormField"
// import Draggable from "vuedraggable"

const root = 'fields/tags'

export default {
    components: {
        // Draggable,
    },

    mixins: [FormField, HandlesValidationErrors],

    data: () => ({
        castArray: false,
        newTag: '',
        options: [],

        // dragOptions: {
        //     delay: 0,
        //     touchStartThreshold: 0,
        //     forceFallback: true,
        //     animation: 150,
        //     ghostClass: "ghost",
        //     handle: ".cursor-move",
        //     dragClass: "sortable-drag",
        // },
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

        tags() {
            return this.value
        },
    },

    methods: {
        search(query, force = false) {
            if (this.loading && !force)
                return

            this.loading = true

            App.api.request({
                url: root + '/search',
                params: {
                    query: query,
                },
            }).then(({data}) => {
                this.loading = false
                this.options = data
            })
        },

        addTag(value) {
            if (!value)
                return

            if (this.loading)
                return

            this.loading = true

            App.api.request({
                method: 'POST',
                url: root,
                params: {
                    value,
                },
            }).then(({message}) => {
                this.loading = false

                let values = this.tags

                if (!values.find(item => item == this.newTag)) {
                    values.push(this.newTag)
                }

                this.handleChange(values)

                this.newTag = ''
                // if (message)
                //     this.$toasted.success(message)
            })
        },

        removeTag(value) {
            let values = this.tags

            this.handleChange(values.filter(item => item != value))
        },

        // change(value) {
        //     this.handleChange(value)
        // },
    },
}
</script>

