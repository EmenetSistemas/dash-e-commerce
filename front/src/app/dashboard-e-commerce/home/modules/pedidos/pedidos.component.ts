import { Component, OnInit } from '@angular/core';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-pedidos',
	templateUrl: './pedidos.component.html',
	styleUrls: ['./pedidos.component.css']
})
export class PedidosComponent implements OnInit{
	protected statusPedidos: any[] = [];
	protected statusSeleccionados: any[] = [];

	protected columnasPedidos : any = {
		"pkTblPedido" 		 : "#",
		"nombre" 			 : "Cliente",
		"productos" 		 : "Productos",
		"articulos" 		 : "Articulos",
		"fechaPedido" 		 : "Pedido",
		"fechaEntrega" 		 : "Entrega",
		"nombreStatus" 		 : "Status"
	};

	protected tableConfig : any = {
		"fechaPedido" : {
			"dateRange" : true,
			"center" : true
		},
		"fechaEntrega" : {
			"dateRange" : true,
			"center" : true
		}
	}

	protected listaPedidosStatus : any = [];
	
	constructor(
		private mensajes: MensajesService,
		private apiProductos: ProductosService
	) { }

	async ngOnInit () : Promise<void> {
		this.mensajes.mensajeEsperar();
		await Promise.all([
			this.obtenerStatusPedidosSelect()
		])
		this.mensajes.cerrarMensajes();
	}

	private obtenerStatusPedidosSelect () : Promise<any> {
		return this.apiProductos.obtenerStatusPedidosSelect().toPromise().then(
			respuesta => {
				this.statusPedidos = respuesta.data;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected async refreshStatusPedidos(): Promise<void> {
		this.mensajes.mensajeEsperar();
		await this.obtenerStatusPedidosSelect();
		this.mensajes.mensajeGenericoToast('Se actualizó la lista de Status Pedidos', 'success');
	}

	protected cambioDeSeleccion(data: any): void {
		if (data.from == 'statusPedidos') {
			this.statusSeleccionados = data.selectedOptions;
		}
	}

	protected obtenerPedidosPorStatus () : void {
		this.mensajes.mensajeEsperar();
		const arregloSocios = { status : this.statusSeleccionados.map(({value}) => value) };

		this.apiProductos.obtenerPedidosPorStatus(arregloSocios).subscribe(
			respuesta => {
				this.listaPedidosStatus = respuesta.data;
				this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected limpiarTabla () : void {
		this.listaPedidosStatus = [];
	}

	protected canGet() : boolean {
		return !(this.statusSeleccionados.length > 0);
	}

	protected canExport() : boolean {
		return !(this.listaPedidosStatus.length > 0);
	}

	protected canClean() : boolean {
		return !(this.listaPedidosStatus.length > 0);
	}
}