import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-productos-pendientes',
	templateUrl: './productos-pendientes.component.html',
	styleUrls: ['./productos-pendientes.component.css']
})
export class ProductosPendientesComponent implements OnInit {
	protected columnasProductosPendientes : any = {
		'identificador_mbp'	: '#',
		'descripcion' 		: 'Producto',
		'precio' 			: 'Precio',
		'stock' 			: 'Stock'
	};

	protected tableConfigPendientes : any = {
		"identificador_mbp" : {
			"updateColumn" : true,
			"value" : "id",
			"idModal" : "modificacionProducto"
		},
		"precio" : {
			"moneyColumn" : true,
			"style" : {
				"font-weight" : "bold"
			}
		}
	};

	protected columnasProductosTienda = {
		'id'	 	: '#',
		'nombre' 	: 'Producto',
		'apartado'  : 'Apartado',
		'categoria' : 'Categoría',
		'precio' 	: 'Precio',
		'stock'  	: 'Stock'
	};

	protected tableConfig = {
		"id" : {
			"updateColumn" : true,
			"value" : "id",
			"idModal" : "modificacionProducto"
		},
		"precio" : {
			"moneyColumn" : true,
			"style" : {
				"font-weight" : "bold"
			}
		},
		"apartado" : {
			"selectColumn": true,
			"selectOptions": []
		},
		"categoria" : {
			"selectColumn": true,
			"selectOptions": []
		}
	};

	protected listaProductos : any[] = [];
	protected datosMostrar : string = '';

	constructor(
		private apiProductos : ProductosService,
		private mensajes : MensajesService,
		private route : ActivatedRoute,
		private dataService : DataService
	) {
		this.dataService.realizarClickConsultaPorductos.subscribe(() => {
			this.obtenerProductosFunction().then(() => {
				this.mensajes.mensajeGenerico('Se actualizó con éxito, ahora el producto se puede visualizar en tienda en línea y en el apartado de productos en tienda', 'success', 'Producto en tienda');
			});
		});
	}

	ngOnInit () : any {
		this.mensajes.mensajeEsperar();
		this.obtenerCategoriasApartados().then(() => {
			this.route.params.subscribe(params => {
				this.listaProductos = [];
				this.datosMostrar = params['datos'];
			});
			this.mensajes.cerrarMensajes();
		});
	}

	private async obtenerCategoriasApartados () : Promise<any> {
		return this.apiProductos.obtenerCategoriasApartados().toPromise().then(
			respuesta => {
				const data = respuesta.data.categoriasApartados;
				this.tableConfig.apartado.selectOptions = data.flatMap((categoria : any) => categoria.apartados.map((apartado : any) => apartado.nombre));
				this.tableConfig.categoria.selectOptions = data.map((categoria : any) => categoria.nombre);
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected obtenerProductos () : void {
		this.mensajes.mensajeEsperar();
		this.obtenerProductosFunction().then(() => {
			this.mensajes.mensajeGenericoToast('Se obtuvieron los porductos pendientes con éxito', 'success');
		});
	}

	private async obtenerProductosFunction () : Promise<void> {
		return this.apiProductos.obtenerProductos(this.datosMostrar).toPromise().then(
			respuesta => {
				this.listaProductos = respuesta.data.productos;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	public limpiarTabla () : void {
		this.listaProductos = [];
	}
}