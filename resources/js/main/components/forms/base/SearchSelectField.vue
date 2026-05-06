<template>
    <div class="select-field">
        <input
            class="form-field"
            :class="{ 'form-field--invalid' : hasErrors }"
            :id="id"
            :name="name"
            :aria-invalid="hasErrors ? 'true' : null"
            :aria-describedby="describedBy || null"
            type="text"
            v-model="filter"
            placeholder="Selecciona una opción..."
            @focus="showOptions = true"
            @blur="hideOptions"
            @input="filterOptions"
        />
        <ul v-show="showOptions" class="options-list">
            <li v-for="(option, key) in filteredOptions"
                :key="key"
                @mousedown.prevent="selectOption(key, option)"
                v-text="option"
                class="option-item"
            ></li>
        </ul>
    </div>
</template>

<script>
import FormField from '../../../mixins/FormField.js';

export default {
    mixins: [FormField],

    props: {
        /**
         * Receive an initial selected value.
         */
        initial: {
            type: [Number, String],
            required: false,
            default: ''
        },

        /**
         * An object of values and descriptions to populate the options
         * inside the select field.
         */
        options: {
            type: [Object, Array],
            required: true
        }
    },

    data() {
        return {
            filter: this.initial ? this.options[this.initial] || '' : '',
            showOptions: false,
            selectedOption: this.initial,
            filteredOptions: this.options
        };
    },

    methods: {
        filterOptions() {
            const filter = this.filter.toLowerCase();
            this.filteredOptions = Object.keys(this.options).reduce((acc, key) => {
                if (this.options[key].toLowerCase().includes(filter)) {
                    acc[key] = this.options[key];
                }
                return acc;
            }, {});
        },

        selectOption(key, option) {
            this.filter = option;
            this.selectedOption = key;
            this.showOptions = false;
            this.$emit('input', key);
        },

        hideOptions() {
            // Delay hiding to allow option selection
            setTimeout(() => {
                this.showOptions = false;
            }, 100);
        }
    },

    mounted() {
        this.$set(this.$parent.fields, this.name, this.initial);
    }
};
</script>

<style>
.select-field {
    position: relative;
}

.options-list {
    position: absolute;
    width: 100%;
    border: 1px solid #ccc;
    background: white;
    z-index: 1000;
    max-height: 150px;
    overflow-y: auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    padding: 0;
    margin: 0;
    list-style: none;
}

.option-item {
    padding: 10px;
    cursor: pointer;
}

.option-item:hover {
    background-color: #f0f0f0;
}
</style>
