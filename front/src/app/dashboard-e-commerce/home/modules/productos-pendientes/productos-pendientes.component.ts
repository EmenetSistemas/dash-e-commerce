import { Component, OnInit } from '@angular/core';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-productos-pendientes',
	templateUrl: './productos-pendientes.component.html',
	styleUrls: ['./productos-pendientes.component.css']
})
export class ProductosPendientesComponent implements OnInit {
	protected columnasProductos : any = {
		'identificador_mbp'	: '#',
		'descripcion' 		: 'Producto',
		'precio' 			: 'Precio',
		'stock' 			: 'Stock'
	};

	protected tableConfig : any = {
		"precio" : {
			"moneyColumn" : true,
			"style" : {
				"font-weight" : "bold"
			}
		},
		"identificador_mbp" : {
			"updateColumn" : true,
			"value" : "identificador_mbp",
			"idModal" : "modificacionProducto"
		},
	};

	protected listaProductos : any[] = [];

	constructor(
		private apiProductos : ProductosService,
		private mensajes : MensajesService
	) {}

	ngOnInit(): void {
	}

	protected obtenerProductosPendientes () : void {
		this.mensajes.mensajeEsperar();
		this.apiProductos.obtenerProductosPendientes().subscribe(
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