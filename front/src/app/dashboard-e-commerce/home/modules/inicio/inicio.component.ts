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
	protected columnasProductosTienda = {
		'id': '#',
		'nombre': 'Producto',
		'apartado': 'Apartado',
		'categoria': 'Categoría',
		'precio': 'Precio',
		'stock': 'Stock'
	};

	protected tableConfig = {
		"precio": {
			"moneyColumn": true,
			"style": {
				"font-weight": "bold"
			}
		},
		"apartado": {
			"selectColumn": true,
			"selectOptions": []
		},
		"categoria": {
			"selectColumn": true,
			"selectOptions": []
		}
	};

	protected listaProductosRecientes: any[] = [];

	protected cantidadProductosPendientes : string = '';
	protected cantidadUsuariosTienda : string = '';
	protected cantidadPedidosPendientes : string = '';
	protected totalesVentas : any = {};
	protected cantidadProductosTienda : string = '';

	constructor(
		private mensajes : MensajesService,
		private apiProductos : ProductosService,
		private apiUsuarios : UsuariosService
	) { }
	ngOnInit(): void {
		this.obtenerCategoriasApartados();
		this.obtenerProductosAgregadosRecientes();
		this.obtenerCantidadProductos('cantidadPendientes');
		this.obtenerCantidadUsuariosTienda();
		this.obtenerCantidadPedidosPendientes();
		this.obtenerTotalesDashboard();
		this.obtenerCantidadProductos('cantidadTienda');
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

	private obtenerCantidadPedidosPendientes () : Promise<any> {
		return this.apiProductos.obtenerCantidadPedidosPendientes().toPromise().then(
			respuesta => {
				this.cantidadPedidosPendientes = respuesta;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerTotalesDashboard () : Promise<any> {
		return this.apiProductos.obtenerTotalesDashboard().toPromise().then(
			respuesta => {
				this.totalesVentas = respuesta.data;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerCategoriasApartados (): Promise<any> {
		return this.apiProductos.obtenerCategoriasApartados().toPromise().then(
			respuesta => {
				const data = respuesta.data.categoriasApartados;
				this.tableConfig.apartado.selectOptions = data.flatMap((categoria: any) => categoria.apartados.map((apartado: any) => apartado.nombre));
				this.tableConfig.categoria.selectOptions = data.map((categoria: any) => categoria.nombre);
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerProductosAgregadosRecientes () : Promise<any> {
		return this.apiProductos.obtenerProductosAgregadosRecientes().toPromise().then(
			respuesta => {
				this.listaProductosRecientes = respuesta.data.productosRecientes;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}
}