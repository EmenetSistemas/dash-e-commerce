import { Component } from '@angular/core';
import { PlanesInternetService } from 'src/app/dashboard-e-commerce/services/planes-internet/planes-internet.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ExcelService } from 'src/app/shared/util/excel.service';
import FGenerico from 'src/app/shared/util/funciones-genericas';

@Component({
	selector: 'app-consulta-planes',
	templateUrl: './consulta-planes.component.html',
	styleUrls: ['./consulta-planes.component.css']
})
export class ConsultaPlanesComponent extends FGenerico {
	protected columnasPlanes: any = {
		'pkTblPlan': '#',
		'plan': 'Plan',
		'precio': 'Precio',
		'periodo' : 'Periodo',
		'tipoPlan': 'Tipo'
	};

	protected listaPlanes: any = [];

	protected tableConfig: any = {
		"pkTblPlan": {
			"detailColumn": true,
			"value": "pkTblPlan",
			"idModal": "detallePlan"
		},
		"precio": {
			"moneyColumn": true,
			"style": {
				"font-weight": "bold"
			}
		},
		"periodo": {
			"selectColumn": true,
			"selectOptions": [
				'Anual',
				'Mensual'
			]
		},
		"tipoPlan": {
			"selectColumn": true,
			"selectOptions": [
				'Plan',
				'Paquete'
			]
		}
	};

	constructor(
		private mensajes: MensajesService,
		private excelService: ExcelService,
		private apiPlanes: PlanesInternetService,
		private dataService: DataService
	) {
		super();
		this.dataService.realizarClickConsultaPlanes.subscribe(() => {
			this.obtenerPlanesInternetFunction().then(() => {
				this.mensajes.mensajeGenericoToast('Se actualizó el plan con éxito', 'success');
				return;
			});
		});
	}

	protected obtenerPlanesInternet(): void {
		this.mensajes.mensajeEsperar();
		this.obtenerPlanesInternetFunction().then(() => {
			this.mensajes.mensajeGenericoToast('Se obtuvieron los planes con éxito', 'success');
		});
	}

	private async obtenerPlanesInternetFunction(): Promise<any> {
		return this.apiPlanes.obtenerPlanesInternet().toPromise().then(
			respuesta => {
				this.listaPlanes = respuesta.data.planes;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected exportarExcel(): void {
		this.mensajes.mensajeEsperar();

		const nombreExcel = 'Lista de Planes: ' + this.getNowString();

		this.excelService.exportarExcel(
			this.listaPlanes,
			this.columnasPlanes,
			nombreExcel
		);
	}

	protected limpiarTabla(): void {
		this.listaPlanes = [];
	}

	protected canExport(): boolean {
		return !(this.listaPlanes.length > 0);
	}

	protected canClean(): boolean {
		return !(this.listaPlanes.length > 0);
	}
}