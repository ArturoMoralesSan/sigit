<template>
    <div class="box box--lg bg-white b-1 rounded relative">
        <h3>
            Producto {{ index }}
        </h3>

        <button v-if="index > minProducts"
            class="btn btn--danger btn--xs rounded-0 absolute top-0 right-0"
            type="button"
            @click="$emit('remove', index)"
        >
            Eliminar
        </button>
        <div class="row mb-4">
            <div class="md:col-1/2 sm:col">
                <div class="form-control">
                    <label :for="'product' + index + '-producto'">producto</label>
                    <search-select-field
                        :name="'product' + index + '_producto'"
                        v-model="fields['product' + index + '_producto']"
                        :options="productsData"
                        :initial="((typeof assignedProducts[index-1] !== 'undefined') ? assignedProducts[index-1].id.toString() : '')"
                    >
                    </search-select-field>
                    <field-errors :name="'product' + index + '_producto'"></field-errors>
                </div>
            </div>
            <div class="md:col-1/2 sm:col">
                <div class="form-control">
                    <label :for="'product' + index + '-quantity'">Cantidad</label>

                    <text-field :name="'product' + index + '_quantity'"
                        v-model="fields['product' + index + '_quantity']"
                        maxlength="160"
                        :initial="((typeof assignedProducts[index-1] !== 'undefined') ? assignedProducts[index-1].pivot.quantity.toString() : '')"

                    >
                    </text-field>
                    <field-errors :name="'product' + index + '_quantity'"></field-errors>

                </div>
            </div>
            
        </div>
        
    </div>
</template>

<script>
    import SearchSelectField from '../base/SearchSelectField.vue';
    import TextField from '../base/TextField.vue';
    import FieldErrors from '../base/FieldErrors.vue';

    export default {
        components: { SearchSelectField, FieldErrors, TextField },

        props: {
            productsData: {
                type: Object,
                required: true
            },
            index: {
                type: Number,
                required: true
            },

            errors: {
                type: Object,
                required: true
            },

            fields: {
                type: Object,
                required: true
            },
            
            minProducts: {
                required: true,
                type: Number
            },
            assignedProducts: {
                required: true,
                type: Array
            },
        },
        data() {
            return {
                noExistAlert: false,

            };
        },
    };
</script>
