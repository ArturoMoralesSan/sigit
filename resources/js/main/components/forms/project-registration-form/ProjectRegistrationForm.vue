<template>
    <form>
        
        <div class="form-control">
            <label for="user">Usuario</label>
            <select-field 
                name="user" 
                v-model="fields.user" 
                :options="users"
                :initial="((typeof userData !== 'undefined') ? userData : '')"
            >
            </select-field>
            <field-errors name="user"></field-errors>
        </div>
        <div>
            <ProjectAuthorForm v-for="i in fields.product_count" :key="i"
                :index="i"
                :min-products="minProducts"
                :products-data="productsData"
                :assigned-products="assignedProducts"
                :errors="errors"
                :fields="fields"
                @remove="removeProduct"
            >
            </ProjectAuthorForm>

            <p class="pt-4">
                <button v-if="fields.product_count < products"
                    class="btn btn--light mr-4"
                    type="button"
                    @click="fields.product_count++"
                >
                    <img class="mr-1 align-top"
                        :src="$root.path + '/img/icons/plus-circle-primary.svg'"
                        alt=""
                        width="20px"
                    >
                    <span class="align-top">Agregar producto</span>
                </button>

                <span v-if="products > 1 "> Puedes registrar a un máximo de {{ products }} productos.</span>
                <span v-else> Puedes registrar únicamente un producto.</span>
            </p>
        </div>
        <div class="text-center pt-8">
            <form-button class="btn--primary btn--wide">
                Enviar
            </form-button>
        </div>
   </form>
</template>

<script>
    import BaseForm from '../base/BaseForm.vue';
    import ProjectAuthorForm from './ProjectAuthorForm.vue';

    export default {
        extends: BaseForm,

        components: { ProjectAuthorForm },
        props: {
            products: {
                required: true,
                type: Number
            },
            minProducts: {
                required: true,
                type: Number
            },
            users: {
                required: true,
                type: Object
            },
            productsData: {
                required: true,
                type: Object
            },
            assignedProducts: {
                required: true,
                type: Array
            },
            userData: {
                required: true,
                type: String
            },

        },
        data() {
            return {
                firstTime: null,
                fields: {
                    product_count: this.minProducts,
                }
            };
        },
        mounted() {
            if (this.assignedProducts.length != 0) {
                this.fields.product_count = this.assignedProducts.length;
            }

        },
        watch: {
            firstTime: function(val) {
                this.fields._method = val === false ? 'patch' : 'post';
            }
        },

        methods: {
            /**
             * Copy all author's fields from one card to another.
             *
             * @param {Integer} source
             * @param {Integer} target
             */
            copyAuthorFields(source, target) {
                const regex = new RegExp('^product' + source + '_');

                this.deleteAuthorFields(target);

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        this.$set(this.fields, field.replace(source, target), this.fields[field]);
                    }
                }
            },


            /**
             * Delete all fields for the given author.
             *
             * @param {Integer} index
             */
            deleteAuthorFields(index) {
                const regex = new RegExp('^product' + index + '_');

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        delete this.fields[field];
                    }
                }
            },


            /**
             * Copy all necessary author fields to move their index
             * and then remove the last card.
             *
             * @param {Integer} index
             */
            removeProduct(index) {
                for (let i = 0; i < this.fields.product_count - index; i ++) {
                    this.copyAuthorFields(index + i + 1, index + i);
                }

                this.fields.product_count--;

                this.deleteAuthorFields(this.fields.product_count + 1);
            }
        }
    };
</script>
