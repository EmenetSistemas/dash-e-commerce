import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UsuariosService } from 'src/app/dashboard-e-commerce/services/usuarios/usuarios.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
	selector: 'app-detalle-cliente',
	templateUrl: './detalle-cliente.component.html',
	styleUrls: ['./detalle-cliente.component.css']
})
export class DetalleClienteComponent extends FGenerico implements OnInit{
	@Input() idDetalle: number = 0;

	protected formDatosPersonales! : FormGroup;
  	protected formDetalleDomicilio! : FormGroup;

	protected usuario : any = [];

	constructor(
		private modalService : ModalService,
		private fb : FormBuilder,
		private apiUsuarios : UsuariosService,
		private mensajes : MensajesService
	) {
		super();
	}

	async ngOnInit(): Promise<any> {
		this.mensajes.mensajeEsperar();
		this.crearFormDatosPersonales();
		this.crearFormDetalleDomicilio();
		await this.obtenerDetalleCliente();
		this.mensajes.cerrarMensajes();
	}

	private crearFormDatosPersonales () : void {
		this.formDatosPersonales = this.fb.group({
			nombre 			: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			aPaterno 		: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			aMaterno 		: [{value : null, disabled: true}, [Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			telefono 		: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[0-9]*')]],
			correo 			: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*'), Validators.email]],
			password 		: [{value : null, disabled: true}, []],
			confirmPassword	: [{value : null, disabled: true}, []]
		});
	}

	private crearFormDetalleDomicilio () : void {
		this.formDetalleDomicilio = this.fb.group({
			calle 		: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			noExterior 	: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			cp 			: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[0-9]*')]],
			localidad 	: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			municipio 	: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			estado 		: [{value : null, disabled: true}, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			referencias : [{value : null, disabled: true}, [Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]]
		});
	}

	private obtenerDetalleCliente () : Promise<any> {
		return this.apiUsuarios.obtenerDetalleCliente(this.idDetalle).toPromise().then(
			respuesta => {
				if (respuesta.data.status == 204) {
					localStorage.removeItem('token');
					localStorage.clear();
					return;
				}

				this.usuario = respuesta.data.datosCliente;
				this.cargarFromularios();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFromularios () : void {
		this.formDatosPersonales.get('nombre')?.setValue(this.usuario.nombre);
		this.formDatosPersonales.get('aPaterno')?.setValue(this.usuario.aPaterno);
		this.formDatosPersonales.get('aMaterno')?.setValue(this.usuario.aMaterno);
		this.formDatosPersonales.get('telefono')?.setValue(this.usuario.telefono);
		this.formDatosPersonales.get('correo')?.setValue(this.usuario.correo);

		this.formDetalleDomicilio.get('calle')?.setValue(this.usuario.direccion.calle);
		this.formDetalleDomicilio.get('noExterior')?.setValue(this.usuario.direccion.noExterior);
		this.formDetalleDomicilio.get('cp')?.setValue(this.usuario.direccion.cp);
		this.formDetalleDomicilio.get('localidad')?.setValue(this.usuario.direccion.localidad);
		this.formDetalleDomicilio.get('municipio')?.setValue(this.usuario.direccion.municipio);
		this.formDetalleDomicilio.get('estado')?.setValue(this.usuario.direccion.estado);
		this.formDetalleDomicilio.get('referencias')?.setValue(this.usuario.direccion.referencias);
	}

	public cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}