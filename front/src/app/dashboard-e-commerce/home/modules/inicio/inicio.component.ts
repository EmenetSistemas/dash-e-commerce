import { Component, OnInit } from '@angular/core';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { UsuariosService } from 'src/app/dashboard-e-commerce/services/usuarios/usuarios.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-inicio',
	templateUrl: './inicio.component.html',
	styleUrls: ['./inicio.component.css']
})
export class InicioComponent implements OnInit {
	protected cantidadProductosPendientes : number = 0;
	protected cantidadProductosTienda : number = 0;
	protected cantidadUsuariosTienda : number = 0;

	constructor(
		private mensajes : MensajesService,
		private apiProductos : ProductosService,
		private apiUsuarios : UsuariosService
	) { }
	async ngOnInit(): Promise<any> {
		this.mensajes.mensajeEsperar();
		await Promise.all([
			this.obtenerCantidadProductos('cantidadPendientes'),
			this.obtenerCantidadProductos('cantidadTienda'),
			this.obtenerCantidadUsuariosTienda()
		]);
		this.mensajes.cerrarMensajes();
	}

	private obtenerCantidadProductos (section : string) : Promise<any> {
		return this.apiProductos.obtenerProductos(section).toPromise().then(
			respuesta => {
				if (section == 'cantidadPendientes') {
					this.cantidadProductosPendientes = respuesta.data.productos;
				} else {
					this.cantidadProductosTienda = respuesta.data.productos;
				}
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerCantidadUsuariosTienda () : Promise<any> {
		return this.apiUsuarios.obtenerCantidadUsuariosTienda().toPromise().then(
			respuesta => {
				this.cantidadUsuariosTienda = respuesta;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}
}