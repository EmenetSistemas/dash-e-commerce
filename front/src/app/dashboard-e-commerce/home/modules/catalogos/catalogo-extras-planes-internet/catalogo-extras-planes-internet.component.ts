import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PlanesInternetService } from 'src/app/dashboard-e-commerce/services/planes-internet/planes-internet.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';
import FGenerico from 'src/app/shared/util/funciones-genericas';

@Component({
	selector: 'app-catalogo-extras-planes-internet',
	templateUrl: './catalogo-extras-planes-internet.component.html',
	styleUrls: ['./catalogo-extras-planes-internet.component.css']
})
export class CatalogoExtrasPlanesInternetComponent extends FGenerico implements OnInit{
	protected formCaracteristica!: FormGroup;

	protected mostrarUpdate : boolean = false;
	private idCaracteristicaMod : number = 0;

	protected columnasCaracteristicas: any = {
		'icono'   : 'Icono',
		'nombre'  : 'Característica',
		'actions' : 'Acciones'
	};

	protected tableConfig: any = {
		"icono": {
			"icono" : true,
			"center" : true
		},
		"actions": {
			"noFilter": true,
			"actionFilter": true,
			"actions": [
				{
					"nombre": 'update',
					"titulo": 'Editar',
					"icon": 'bi-exclamation-triangle',
					"bg": 'warning'
				}
			],
			"value": "pkCatCaracteristica"
		}
	};

	protected listaCaracteristicas: any[] = [];

	constructor (
		private modalService: ModalService,
		private fb: FormBuilder,
		private apiPlanes: PlanesInternetService,
		private mensajes: MensajesService
	) {
		super();
	}

	async ngOnInit(): Promise<any> {
		this.crearFormCaracteristica();
		this.mensajes.mensajeEsperar();
		await this.obtenerCaracteristicasPlanes();
		this.mensajes.cerrarMensajes();
	}

	public crearFormCaracteristica () : void {
		this.formCaracteristica = this.fb.group({
			icono : [null, [Validators.required]],
			nombre : [null, [Validators.required]]
		});
	}

	private obtenerCaracteristicasPlanes () : Promise<any> {
		return this.apiPlanes.obtenerCaracteristicasPlanes().toPromise().then(
			respuesta => {
				this.listaCaracteristicas = respuesta.data.caracteristicas;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected registrarCaracteristicaPlan () : void {
		if (this.formCaracteristica.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning', 'Los campos requeridos están marcados con un *');
			return;
		}

		this.mensajes.mensajeEsperar();
		this.apiPlanes.registrarCaracteristica(this.formCaracteristica.value).subscribe(
			respuesta => {
				this.obtenerCaracteristicasPlanes().then(() => {
					this.limpiarForm();

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected actualizarCaracteristicaPlan () : void {
		if (this.formCaracteristica.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning', 'Los campos requeridos están marcados con un *');
			return;
		}

		this.mensajes.mensajeEsperar();
		this.formCaracteristica.value.fkCatCaracteristica = this.idCaracteristicaMod;
		this.apiPlanes.actualizarCaracteristica(this.formCaracteristica.value).subscribe(
			respuesta => {
				this.obtenerCaracteristicasPlanes().then(() => {
					this.ocultarModificacionCaracteristica();
					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected realizarAccion (data : any) : void {
		switch (data.action) {
			case 'update':
				this.idCaracteristicaMod = data.idAccion;
				const dataActualizar = this.listaCaracteristicas.find(categoria => categoria.pkCatCaracteristica == data.idAccion);
				this.mostrarUpdate = true;
				this.formCaracteristica.get('nombre')?.setValue(dataActualizar.nombre);
				this.formCaracteristica.get('icono')?.setValue(dataActualizar.icono);
			break;
		}
	}

	protected ocultarModificacionCaracteristica () : void {
		this.mostrarUpdate = false;
		this.limpiarForm();
	}

	private limpiarForm () : void {
		this.formCaracteristica.reset();
	}

	public cerrarModal(): void {
		this.modalService.cerrarModal();
	}
}