import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { UsuariosService } from 'src/app/dashboard-e-commerce/services/usuarios/usuarios.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
	selector: 'app-detalle-pedido',
	templateUrl: './detalle-pedido.component.html',
	styleUrls: ['./detalle-pedido.component.css']
})
export class DetallePedidoComponent extends FGenerico implements OnInit{
	@Input() idDetalle: number = 0;

	protected formDatosPersonalesUsuario! : FormGroup;

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
		await this.obtenerDetallePedido();
		this.mensajes.cerrarMensajes();
	}

	private crearFormDatosPersonalesUsuario() {
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

	private obtenerDetallePedido () : Promise<any> {
		return this.apiProductos.obtenerDetallePedido(this.idDetalle).toPromise().then(
			respuesta => {
				this.datosUsuario = respuesta.data.datosUsuario[0];
				this.productosPedido = respuesta.data.productosPedido;
				this.pedido = respuesta.data.detallePedido[0];
				this.cargarFormularioCliente();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFormularioCliente () : void {
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