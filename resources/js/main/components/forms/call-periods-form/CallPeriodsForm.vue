<template>
	<form>
		<h1 class="h3 mb-8">
            Informes
        </h1>

        <div v-if="pending_period !== null">
			<div class="box b-1 rounded bg-gray-lighten-4 mb-12">
				<div class="mb-6">
					<p class="h5 text-center mb-8">{{ pending_period.type_project }}</p>
				    <p class="size-caption mb-6"><strong>Periodo:</strong> {{ pending_period.start_date }} al {{ pending_period.finish_date }}</p>

					<p class="size-caption"><strong>Documentos necesarios:</strong></p>
					<p :class="'mb-2 d-flex left-auto'" v-for="file in pending_period.files">
						- {{ file.name }} 
						<span class="file-icon has-check" v-if="file.hasFile"></span>
						<span class="announcement-alert has-warning" v-if="!file.hasFile">Pendiente</span>
					</p>

					<div v-for="(file, index) in pending_period.files" v-if="!file.hasFile">

						<div class="form-control file-input mt-8">
							<label :for="'document-' + file.key"> {{ file.name }}</label>

							<file-field :name="'document_' + file.key.replace(/-/g, '_')" v-model="fields['document_' + file.key.replace(/-/g, '_')]" aria-describedby="document-specs"></file-field>

							<field-errors :name="'document_' + file.key.replace(/-/g, '_')"></field-errors>

							<ul id="document-specs" class="description color-gray-darken-1">
								<li>
									Tamaño máximo: 5 MB.
								</li>
								<li>
									Sólo archivos con extensión pdf.
								</li>
							</ul>
						</div>
			        </div>
					
				</div>

				<div class="text-center pt-8 mb-4">
		            <form-button class="btn--primary btn--wide" :disabled="countFiles()">
		                Enviar
		            </form-button>
		        </div>
			</div>
        </div>

		<div class="mb-12" v-else>
			<p>
				{{ finished ?
				'Has finalizado satisfactoriamente el proceso del proyecto.' :
					canceled ?
					'El proceso del proyecto ha sido cancelado.' :
					'No cuentas con informes pendientes por subir.' }}
			</p>
		</div>

		<h4 class="mb-12">Calendario de informes</h4>

		<div v-for="period in periods">

			<div class="message mb-8" v-if="period.status == 'Pendiente' && period.isTime && !canceled">
	            <div class="message__body message__body--bordered b-1 ">
		        	<p class="h5 has-warning pb-1">{{ period.description }}</p>

					<p class="size-caption message__item"><strong>Estado:</strong>  {{ period.status }} </p>
					<p class="size-caption message__item"><strong>Periodo:</strong> {{ period.start_date }} al {{ period.finish_date }}</p>
					<p class="size-caption message__item"><strong>Documento{{ period.files.length > 0 ? 's' : ''}} necesario{{ period.files.length > 0 ? 's' : ''}}:</strong> </p>
					<p class="size-caption m-0" v-for="file in period.files">- {{ file.name }}</p>
					<div v-if="period.observations">
						<hr class="spaced" >
						<p class="size-caption message__item my-2"><strong>Observaciones:</strong></p>
						<p class="size-caption m-0">{{ period.observations }}</p>
					</div>
	            </div>
	        </div>

			<div class="box b-1 rounded mb-8 card--period" v-else="period.status !== 'Pendiente'">
	        	<p :class="'h5 pb-1 '+period.icon">{{ period.description }}</p>

				<p class="size-caption message__item"><strong>Estado:</strong> {{ period.status == 'Recibido' ? 'Enviado' : period.status }} </p>
				<p class="size-caption message__item"><strong>Periodo:</strong> {{ period.start_date }} al {{ period.finish_date }}</p>
				<p class="size-caption message__item"><strong>Documento{{ period.files.length > 0 ? 's' : ''}} necesario{{ period.files.length > 0 ? 's' : ''}}:</strong> </p>
				<p class="size-caption m-0 d-flex" v-for="file in period.files">- {{ file.name }}
					<span :class="'file-icon ' + (file.hasFile ? 'has-check' : 'has-warning')" v-if="period.isCanceled"></span></p>
				<div v-if="period.observations">
					<hr class="spaced" >
					<p class="size-caption message__item my-2"><strong>Observaciones:</strong></p>
					<p class="size-caption m-0">{{ period.observations }}</p>
				</div>
	        </div>
		</div>
	</form>
</template>

<script>
	import BaseForm from '../base/BaseForm.vue';

	export default {
		extends: BaseForm,

		props: {
            status: {
                type: Object,
                required: true
            },
            pending: {
                type: Object,
                required: false
            },
            periods: {
                type: Array,
                required: true
            },
            finished: {
                type: Boolean,
                required: false
            },
            canceled: {
                type: Boolean,
                required: false
            },
            project: {
                type: Number,
                required: false
            }
        },
		data() {
			return {
				status_period: '',
				pending_period: '',
				all_periods: '',
				is_finished: '',
				is_canceled: '',
				project_id: '',
			}
		},
		mounted () {

			this.status_period  = this.status;
			this.pending_period = this.pending;
			this.all_periods    = this.periods;
			this.is_finished    = this.finished;
			this.is_canceled    = this.canceled;
			this.project_id     = this.project;
		},
 		methods: {
			countFiles() {				
				return Object.keys(this.fields).length > 1 ? false : true;
			}
    	}
	}
</script>