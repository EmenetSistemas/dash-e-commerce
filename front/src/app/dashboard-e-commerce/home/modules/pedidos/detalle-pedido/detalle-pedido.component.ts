import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';
import FGenerico from 'src/app/shared/util/funciones-genericas';

@Component({
	selector: 'app-detalle-pedido',
	templateUrl: './detalle-pedido.component.html',
	styleUrls: ['./detalle-pedido.component.css']
})
export class DetallePedidoComponent extends FGenerico implements OnInit{
	@Input() idDetalle: number = 0;

	protected formDatosPersonalesUsuario! : FormGroup;
	protected formDatosPedido! : FormGroup;

	protected columnasProductosPedidos : any = {
		'identificador_mbp' : '#',
		'nombre' 			: 'Nombre',
		'descripcion' 		: 'Descripción',
		'cantidad' 			: 'Cantidad'
	};

	protected datosUsuario : any = {};
	protected productosPedido : any = [];
	protected pedido : any = {};

	constructor (
		private mensajes : MensajesService,
		private modalService : ModalService,
		private fb : FormBuilder,
		private apiProductos : ProductosService,
		private dataService : DataService
	) {
		super();
	}

	async ngOnInit(): Promise<any> {
		this.mensajes.mensajeEsperar();
		this.crearFormDatosPersonalesUsuario();
		this.crearFormDatosPedido();
		await this.obtenerDetallePedido();
		this.mensajes.cerrarMensajes();
	}

	private crearFormDatosPersonalesUsuario () : void {
		this.formDatosPersonalesUsuario = this.fb.group({
			nombre 			: [{value:null, disabled:true}],
			aPaterno 		: [{value:null, disabled:true}],
			aMaterno 		: [{value:null, disabled:true}],
			telefono 		: [{value:null, disabled:true}],
			correo 			: [{value:null, disabled:true}],
			calle 			: [{value:null, disabled:true}],
			noExterior 		: [{value:null, disabled:true}],
			cp 				: [{value:null, disabled:true}],
			localidad 		: [{value:null, disabled:true}],
			municipio 		: [{value:null, disabled:true}],
			estado 			: [{value:null, disabled:true}],
			referencias 	: [{value:null, disabled:true}]
		});
	}

	private crearFormDatosPedido () : void {
		this.formDatosPedido = this.fb.group({
			fechaPedido 		 : [{value:null, disabled:true}],
			fechaEntregaEstimada : [{value:null, disabled:true}, Validators.required],
			fechaEntrega 	 	 : [{value:null, disabled:true}]
	});
	}

	private obtenerDetallePedido () : Promise<any> {
		return this.apiProductos.obtenerDetallePedido(this.idDetalle).toPromise().then(
			respuesta => {
				this.datosUsuario = respuesta.data.datosUsuario[0];
				this.productosPedido = respuesta.data.productosPedido;
				this.pedido = respuesta.data.detallePedido[0];
				this.cargarFormularios();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFormularios () : void {
		this.formDatosPersonalesUsuario.get('nombre')?.setValue(this.datosUsuario.nombre);
		this.formDatosPersonalesUsuario.get('aPaterno')?.setValue(this.datosUsuario.aPaterno);
		this.formDatosPersonalesUsuario.get('aMaterno')?.setValue(this.datosUsuario.aMaterno);
		this.formDatosPersonalesUsuario.get('telefono')?.setValue(this.datosUsuario.telefono);
		this.formDatosPersonalesUsuario.get('correo')?.setValue(this.datosUsuario.correo);
		this.formDatosPersonalesUsuario.get('calle')?.setValue(this.datosUsuario.calle);
		this.formDatosPersonalesUsuario.get('noExterior')?.setValue(this.datosUsuario.noExterior);
		this.formDatosPersonalesUsuario.get('cp')?.setValue(this.datosUsuario.cp);
		this.formDatosPersonalesUsuario.get('localidad')?.setValue(this.datosUsuario.localidad);
		this.formDatosPersonalesUsuario.get('municipio')?.setValue(this.datosUsuario.municipio);
		this.formDatosPersonalesUsuario.get('estado')?.setValue(this.datosUsuario.estado);
		this.formDatosPersonalesUsuario.get('referencias')?.setValue(this.datosUsuario.referencias);
		this.formDatosPedido.get('fechaPedido')?.setValue(this.pedido.fechaPedido);
		this.formDatosPedido.get('fechaEntregaEstimada')?.setValue(this.pedido.fechaEntregaEstimada);
		this.formDatosPedido.get('fechaEntrega')?.setValue(this.pedido.fechaEntrega);
		if (this.pedido.fechaEnvio == null) {
			this.formDatosPedido.get('fechaEntregaEstimada')?.enable();
		}
	}

	protected cambioFechaEntregaEstimada () : void {
		if (this.formDatosPedido.invalid) {
			this.mensajes.mensajeGenerico('Es necesario colocar una fecha válida para poder modificar la fecha estimada de entrega.', 'warning', 'Fecha invalida');
			return;
		}

		this.mensajes.mensajeEsperar();

		const data = {
			'fechaEntregaEstimada' : this.formDatosPedido.value.fechaEntregaEstimada,
			'idPedido' : this.idDetalle
		};

		this.apiProductos.actualizarFechaEstimadaEntrega(data).subscribe(
			respuesta => {
				if (respuesta.error == 203) {
					this.formDatosPedido.get('fechaEntregaEstimada')?.setValue(this.pedido.fechaEntregaEstimada);
					this.mensajes.mensajeGenerico(respuesta.mensaje, 'error', 'Fecha invalida');
					return;
				}

				this.obtenerDetallePedido().then(() => {
					this.dataService.realizarClickConsultaPedidos.emit();
					this.mensajes.mensajeGenerico(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected async enviarPedido () : Promise<void> {
		this.mensajes.mensajeConfirmacionCustom('Favor de asegurarse que los porductos y la cantidad de los mismos sean los solicitados en la compra. ¿Está seguro de cambiar el status del pedido y enviar los productos?', 'question', 'Enviar Productos Compra').then(
			respuesta => {
				if (respuesta.isConfirmed) {
					this.mensajes.mensajeEsperar();
					this.apiProductos.enviarPedido(this.idDetalle).subscribe(
						respuesta => {
							this.obtenerDetallePedido().then(() => {
								this.dataService.realizarClickConsultaPedidos.emit();
								this.mensajes.mensajeGenerico(respuesta.mensaje, 'success');
							});
						}, error => {
							this.mensajes.mensajeGenerico('error', 'error');
						}
					);
				}
			}
		);
	}

	protected entregarPedido () : void {
		this.mensajes.mensajeConfirmacionCustom('Favor de asegurarse que los porductos se encuentren en excelentes condiciones para su entrega ¿Está seguro de cambiar el status del pedido y entregar los productos?', 'warning', 'Entregar Productos').then(
			respuesta => {
				if (respuesta.isConfirmed) {
					this.mensajes.mensajeEsperar();
					this.apiProductos.entregarPedido(this.idDetalle).subscribe(
						respuesta => {
							this.obtenerDetallePedido().then(() => {
								this.dataService.realizarClickConsultaPedidos.emit();
								this.mensajes.mensajeGenerico(respuesta.mensaje, 'success');
							});
						}, error => {
							this.mensajes.mensajeGenerico('error', 'error');
						}
					);
				}
			}
		);
	}

	public cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}