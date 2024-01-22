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
	protected columnasProductos : any = {};

	protected tableConfig : any = {};

	protected listaProductos : any[] = [];
	protected datosMostrar : string = '';

	private apartados : any[] = [];
	private categorias : any[] = [];

	constructor(
		private apiProductos : ProductosService,
		private mensajes : MensajesService,
		private route : ActivatedRoute,
		private dataService : DataService
	) {
		this.dataService.realizarClickConsultaPorductos.subscribe(() => {
			this.obtenerProductos();
		});
		this.mensajes.mensajeEsperar();
		this.obtenerCategoriasApartados().then(() => {
			this.route.params.subscribe(params => {
				this.listaProductos = [];
				this.datosMostrar = params['datos'];
				if (this.datosMostrar == 'pendientes') {
					this.columnasProductos = {
						'identificador_mbp'	: '#',
						'descripcion' 		: 'Producto',
						'precio' 			: 'Precio',
						'stock' 			: 'Stock'
					};
	
					this.tableConfig = {
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
				} else {
					this.columnasProductos = {
						'id'	 	: '#',
						'nombre' 	: 'Producto',
						'apartado'  : 'Apartado',
						'categoria' : 'Categoría',
						'precio' 	: 'Precio',
						'stock'  	: 'Stock'
					};
	
					this.tableConfig = {
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
							"selectOptions": this.apartados
						},
						"categoria" : {
							"selectColumn": true,
							"selectOptions": this.categorias
						}
					};
				}
			});
			this.mensajes.cerrarMensajes();
		});
	}

	ngOnInit () : any {
		
	}

	private async obtenerCategoriasApartados () : Promise<any> {
		return this.apiProductos.obtenerCategoriasApartados().toPromise().then(
			respuesta => {
				const data = respuesta.data.categoriasApartados;
				this.apartados = data.flatMap((categoria : any) => categoria.apartados.map((apartado : any) => apartado.nombre));
				this.categorias = data.map((categoria : any) => categoria.nombre);
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected obtenerProductos () : void {
		this.mensajes.mensajeEsperar();
		this.apiProductos.obtenerProductos(this.datosMostrar).subscribe(
			respuesta => {
				this.listaProductos = respuesta.data.productos;
				this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	public limpiarTabla () : void {
		this.listaProductos = [];
	}
}