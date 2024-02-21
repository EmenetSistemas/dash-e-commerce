import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PlanesInternetService } from 'src/app/dashboard-e-commerce/services/planes-internet/planes-internet.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';
import FGenerico from 'src/app/shared/util/funciones-genericas';

@Component({
	selector: 'app-detalle-plan',
	templateUrl: './detalle-plan.component.html',
	styleUrls: ['./detalle-plan.component.css']
})
export class DetallePlanComponent extends FGenerico implements OnInit {
	@Input() idDetalle: number = 0;

	protected formPlan!: FormGroup;

	protected plan: any = {};
	protected mostrarUpdate: boolean = false;
	private idCaracteristicaMod: number = 0;
	protected caracteristicas: any[] = [];

	protected columnasCaracteristicas: any = {
		'nombre'  : 'Característica',
		'actions' : 'Acciones'
	};

	protected tableConfig: any = {
		"actions": {
			"noFilter": true,
			"actionFilter": true,
			"actions": [
				{
					"nombre": 'update',
					"titulo": 'Editar',
					"icon": 'bi-exclamation-triangle',
					"bg": 'warning'
				}, {
					"nombre": 'delete',
					"titulo": 'Eliminar',
					"icon": 'bi-exclamation-octagon',
					"bg": 'danger'
				}
			],
			"value": "pkPlanCaracteristica"
		}
	};

	protected listaCaracteristicas: any[] = [];

	constructor(
		private modal: ModalService,
		private fb: FormBuilder,
		private mensajes: MensajesService,
		private apiPlanes: PlanesInternetService,
		private dataService: DataService
	) {
		super();
	}

	async ngOnInit(): Promise<any> {
		this.crearFormPlan();
		this.mensajes.mensajeEsperar();
		await Promise.all([
			this.obtenerDetallePlan(),
			this.obtenerCaracteristicasPlanes()
		]);
		this.mensajes.cerrarMensajes();
	}

	private crearFormPlan(): void {
		this.formPlan = this.fb.group({
			plan: [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			mensualidad: [null, []],
			anualidad: [{ value: null, disabled: true }, []],
			tipoPlan: [false, [Validators.required]],
			dispositivosSimultaneos: [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			estudioTrabajo: [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			reproduccionVideo: ['', [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			juegoLinea: [false, [Validators.required]],
			transmisiones: [false, [Validators.required]],
			caracteristica: ['', []]
		});
	}

	private obtenerCaracteristicasPlanes (): Promise<any> {
		return this.apiPlanes.obtenerCaracteristicasPlanes().toPromise().then(
			respuesta => {
				this.caracteristicas = respuesta.data.caracteristicas;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerDetallePlan(): Promise<any> {
		return this.apiPlanes.obtenerDetallePlan(this.idDetalle).toPromise().then(
			respuesta => {
				this.cargarFormularioDetallePlan(respuesta.data.plan);
				this.listaCaracteristicas = respuesta.data.caracteristicas;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFormularioDetallePlan(plan: any): void {
		this.formPlan.get('plan')?.setValue(plan.plan);
		this.formPlan.get('mensualidad')?.setValue(plan.mensualidad);
		this.formPlan.get('anualidad')?.setValue(plan.anualidad);
		this.formPlan.get('tipoPlan')?.setValue(plan.tipoPlan == 2);
		this.cambioAnualidad();
		this.formPlan.get('dispositivosSimultaneos')?.setValue(plan.dispositivosSimultaneos);
		this.formPlan.get('estudioTrabajo')?.setValue(plan.estudioTrabajo);
		this.formPlan.get('reproduccionVideo')?.setValue(plan.reproduccionVideo);
		this.formPlan.get('juegoLinea')?.setValue(plan.juegoLinea == 'Sí');
		this.formPlan.get('transmisiones')?.setValue(plan.transmisiones == 'Sí');
	}

	protected cambioAnualidad(): void {
		if (this.formPlan.value.tipoPlan) {
			this.formPlan.get('mensualidad')?.setValue(null);
			this.formPlan.get('mensualidad')?.disable();
			this.formPlan.get('mensualidad')?.clearValidators();
			this.formPlan.get('mensualidad')?.updateValueAndValidity();
			this.formPlan.get('anualidad')?.enable();
			this.formPlan.get('anualidad')?.setValidators([Validators.required, Validators.pattern('[0-9]*')]);
			this.formPlan.get('anualidad')?.updateValueAndValidity();
		} else {
			this.formPlan.get('mensualidad')?.enable();
			this.formPlan.get('mensualidad')?.setValidators([Validators.required, Validators.pattern('[0-9]*')]);
			this.formPlan.get('mensualidad')?.updateValueAndValidity();
			this.formPlan.get('anualidad')?.setValue(null);
			this.formPlan.get('anualidad')?.disable();
			this.formPlan.get('anualidad')?.clearValidators();
			this.formPlan.get('anualidad')?.updateValueAndValidity();
		}
	}

	protected modificarPlan(): void {
		if (this.formPlan.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning', 'Los campos requeridos están marcados con un *');
			return;
		}

		this.mensajes.mensajeEsperar();

		this.formPlan.value.pkTblPlan = this.idDetalle;
		this.formPlan.value.tipoPlan = this.formPlan.value.tipoPlan ? 2 : 1;
		this.formPlan.value.juegoLinea = this.formPlan.value.juegoLinea ? 'Sí' : 'No';
		this.formPlan.value.transmisiones = this.formPlan.value.transmisiones ? 'Sí' : 'No';

		this.apiPlanes.modificarPlan(this.formPlan.value).subscribe(
			respuesta => {
				if (respuesta.status == 203) {
					this.mensajes.mensajeGenerico(respuesta.mensaje, 'warning');
					return;
				}

				this.cerrarModal();
				this.dataService.realizarClickConsultaPlanes.emit();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected registrarCaracteristicaPlan(): void {
		const idCaracteristica = this.formPlan.get('caracteristica')?.value;

		const validaCaract = this.listaCaracteristicas.filter(caract => caract.pkCatCaracteristica == idCaracteristica && caract.pkPlanCaracteristica != this.idCaracteristicaMod);
		if (validaCaract.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer el plan ya cuenta con está característica, se debe agregar una diferente', 'warning', 'Característica existente');
			return;
		}

		this.mensajes.mensajeEsperar();

		const caracteristica = {
			fkPlan : this.idDetalle,
			idCaracteristica : idCaracteristica
		};

		this.apiPlanes.registrarCaracteristicaPlan(caracteristica).subscribe(
			respuesta => {
				this.obtenerDetallePlan().then(() => {
					this.formPlan.get('caracteristica')?.setValue('');

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected realizarAccion(data: any): void {
		switch (data.action) {
			case 'update':
				this.idCaracteristicaMod = data.idAccion;
				const dataActualizar = this.listaCaracteristicas.find(caracteristica => caracteristica.pkPlanCaracteristica == data.idAccion);
				this.mostrarUpdate = true;
				this.formPlan.get('caracteristica')?.setValue(dataActualizar.pkCatCaracteristica);
			break;
			case 'delete':
				this.mensajes.mensajeConfirmacionCustom('Está por elminiar una característica del plan ¿Desea continar con la acción?', 'question', 'Eliminar característica').then(
					respuesta => {
						if (respuesta.isConfirmed) {
							this.mensajes.mensajeEsperar();

							this.apiPlanes.eliminarCaracteristicaPlan(data.idAccion).subscribe(
								respuesta => {
									this.obtenerDetallePlan().then(() => {
										this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
									});
								}, error => {
									this.mensajes.mensajeGenerico('error', 'error');
								}
							);
						}
					}
				);
			break;
		}
	}

	protected actualizarCaracteristicaPlan(): void {
		const idCaracteristica = this.formPlan.get('caracteristica')?.value;

		const validaCaract = this.listaCaracteristicas.filter(caract => caract.pkCatCaracteristica == idCaracteristica && caract.pkPlanCaracteristica != this.idCaracteristicaMod);
		if (validaCaract.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer el plan ya cuenta con está característica, se debe agregar una diferente', 'warning', 'Característica existente');
			return;
		}

		this.mensajes.mensajeEsperar();

		const caracteristica = {
			fkTblPlanCaracteristica : this.idCaracteristicaMod,
			idCaracteristica : idCaracteristica
		};

		this.apiPlanes.actualizarCaracteristicaPlan(caracteristica).subscribe(
			respuesta => {
				this.obtenerDetallePlan().then(() => {
					this.formPlan.get('caracteristica')?.setValue('');

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected ocultarModificacionCaracteristica() {
		this.mostrarUpdate = false;
		this.formPlan.get('caracteristica')?.setValue('');
	}

	protected cerrarModal(): void {
		this.modal.cerrarModal();
	}
}