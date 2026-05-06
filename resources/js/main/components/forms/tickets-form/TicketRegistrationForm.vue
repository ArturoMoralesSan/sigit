<template>
    <form>
        <section class="db-panel">
            <h3 class="db-panel__title">
                Datos del vale
            </h3>
            <div class="md:row">
                <div class="md:col-1/2">
                    <div class="form-control">
                        <label for="req">Solicita</label>
                        <text-field 
                            name="req" 
                            v-model="fields.req" 
                            :initial="((typeof ticket !== 'undefined') ? ticket.req : '')"                           
                        ></text-field>
                        <field-errors name="req"></field-errors>
                    </div>
                </div>
                <div class="md:col-1/2">
                    <div class="form-control">
                        <label for="teacher">Profesor</label>
                        <text-field 
                        name="teacher" 
                        v-model="fields.teacher" 
                        :initial="((typeof ticket !== 'undefined') ? ticket.teacher : '')"></text-field>
                        <field-errors name="teacher"></field-errors>
                    </div>
                </div>
            </div>
            <div class="md:row">
            <div class="md:col-1/3">
                    <div class="form-control">
                        <label for="group">Carrera/grupo</label>
                        <text-field 
                        name="group" 
                        v-model="fields.group" 
                        :initial="((typeof ticket !== 'undefined') ? ticket.group : '')"></text-field>
                        <field-errors name="group"></field-errors>
                    </div>
                </div>
                <div class="md:col-1/3">
                    <div class="form-control">
                        <label for="subject">Asignatura</label>
                        <text-field 
                            name="subject" 
                            v-model="fields.subject" 
                            :initial="((typeof ticket !== 'undefined') ? ticket.subject : '')"></text-field>
                        <field-errors name="subject"></field-errors>
                    </div>
                </div>
                <div class="md:col-1/3">
                    <div class="form-control">
                        <label for="laboratory">Laboratorio</label>
                        <text-field 
                            name="laboratory" 
                            v-model="fields.laboratory" 
                            :initial="((typeof ticket !== 'undefined') ? ticket.laboratory : '')"></text-field>
                        <field-errors name="laboratory"></field-errors>
                    </div>
                </div>
            </div>
            <div class="md:row">
                <div class="md:col-1/2">
                    <div class="form-control">
                        <label for="return_date">Fecha de Devolución</label>
                        <date-field name="return_date" v-model="fields.return_date"
                        :initial="((typeof ticket !== 'undefined') ? ticket.return_date : '')"></date-field>
                        <field-errors name="return_date"></field-errors>
                    </div>
                </div>
                <div class="md:col-1/2">
                    <div class="row">
                        <div class="form-control">
                            <label for="status">Estado</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-control" style="width: 100%;">
                            <select-field class="form-select" name="status" v-model="fields.status"
                                :options="{ 'disponible': 'Disponible', 'prestamo': 'Préstamo', 'baja': 'Baja',
                                    'no disponible': 'No Disponible' }"
                                :initial="((typeof ticket !== 'undefined') ? ticket.status : '')">
                            </select-field>
                            <field-errors name="status"></field-errors>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div>
            <ProductTicketForm v-for="i in fields.product_count" :key="i"
                :index="i"
                :min-products="minProducts"
                :products-data="productsData"
                :assigned-products="assignedProducts"
                :errors="errors"
                :fields="fields"
                @remove="removeProduct"
            >
            </ProductTicketForm>

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
    import ProductTicketForm from './ProductTicketForm.vue';

    export default {
        extends: BaseForm,

        components: { ProductTicketForm },
        props: {
            products: {
                required: true,
                type: Number
            },
            minProducts: {
                required: true,
                type: Number
            },
            productsData: {
                required: true,
                type: Object
            },
            assignedProducts: {
                required: true,
                type: Array
            },
            ticket: {
                required: true,
                type: Object
            },

        },
        data() {
            return {
                firstTime: null,
                fields: {
                    product_count: this.minProducts,
                    voucher_id : this.ticket.id
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
