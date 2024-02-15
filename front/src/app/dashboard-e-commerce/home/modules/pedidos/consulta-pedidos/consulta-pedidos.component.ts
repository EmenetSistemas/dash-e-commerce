import { Component, OnInit } from '@angular/core';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ExcelService } from 'src/app/shared/util/excel.service';
import FGenerico from 'src/app/shared/util/funciones-genericas';

@Component({
	selector: 'app-pedidos',
	templateUrl: './consulta-pedidos.component.html',
	styleUrls: ['./consulta-pedidos.component.css']
})
export class PedidosComponent extends FGenerico implements OnInit{
	protected statusPedidos: any[] = [];
	protected statusSeleccionados: any[] = [];

	protected columnasPedidos : any = {
		"id" 		   			: "#",
		"nombre" 	   			: "Cliente",
		"productos"    			: "Productos",
		"articulos"    			: "Articulos",
		"fechaPedido"  			: "Pedido",
		"fechaEntregaEstimada" 	: "Entrega Estimada",
		"fechaEnvio" 			: "Envió",
		"fechaEntrega" 			: "Entrega",
		"nombreStatus" 			: "Status"
	};

	protected tableConfig : any = {
		"id" : {
			"detailColumn" : true,
			"value" : "pkTblPedido",
			"idModal" : "detallePedido"
		},
		"fechaPedido" : {
			"dateRange" : true,
			"center" : true
		},
		"fechaEnvio" : {
			"dateRange" : true,
			"center" : true
		},
		"fechaEntregaEstimada" : {
			"dateRange" : true,
			"center" : true
		},
		"fechaEntrega" : {
			"dateRange" : true,
			"center" : true
		},
		"nombreStatus" : {
			"selectColumn" : true,
			"selectOptions" : [
				'Pendiente',
				'Enviado',
				'Entregado'
			],
			"dadges" : true,
			"center" : true,
			"dadgesCases" : [
				{
					"text" : "Pendiente",
					"color" : "warning"
				}, {
					"text" : "Enviado",
					"color" : "primary"
				}, {
					"text" : "Entregado",
					"color" : "success"
				}
			]
		},
	}

	protected listaPedidosStatus : any = [];
	
	constructor(
		private mensajes: MensajesService,
		private apiProductos: ProductosService,
		private dataService: DataService,
		private excelService: ExcelService
	) {
		super();
		this.dataService.realizarClickConsultaPedidos.subscribe(() => {
			this.obtenerPedidosPorStatusFunction();
		});
	}

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

	protected cambioDeSeleccion(data: any): void {
		if (data.from == 'statusPedidos') {
			this.statusSeleccionados = data.selectedOptions;
		}
	}

	protected obtenerPedidosPorStatus () : void {
		this.mensajes.mensajeEsperar();
		this.obtenerPedidosPorStatusFunction().then(() => {
			this.mensajes.mensajeGenericoToast('Se consultaron los Pedidos por status seleccionados con éxito', 'success');
		});
	}

	private obtenerPedidosPorStatusFunction () : Promise<any> {
		const arrStatus = { status : this.statusSeleccionados.map(({value}) => value) };
		return this.apiProductos.obtenerPedidosPorStatus(arrStatus).toPromise().then(
			respuesta => {
				this.listaPedidosStatus = respuesta.data;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected exportarExcel () : void {
		this.mensajes.mensajeEsperar();

		const nombreExcel = 'Lista de Pedidos: ' + this.getNowString();

		this.excelService.exportarExcel(
			this.listaPedidosStatus,
			this.columnasPedidos,
			nombreExcel
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