import { Component, OnInit } from '@angular/core';
import { UsuariosService } from 'src/app/dashboard-e-commerce/services/usuarios/usuarios.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-consulta-clientes',
	templateUrl: './consulta-clientes.component.html',
	styleUrls: ['./consulta-clientes.component.css']
})
export class ConsultaClientesComponent implements OnInit{
	protected statusClientes: any[] = [
		{
			checked: false,
			label: 'Activos',
			value: 1
		}, {
			checked: false,
			label: 'Inactivos',
			value: 0
		}
	];
	protected statusSeleccionados: any[] = [];

	protected columnasClientes : any = {
		'pkTblUsuarioTienda' : '#',
		'nombre' 			 : 'Nombre',
		'telefono' 			 : 'Teléfono',
		'correo' 			 : 'Correo',
		'municipio' 		 : 'Municipio',
		'activo' 			 : 'Status'
	};

	protected tableConfig : any = {
		"pkTblUsuarioTienda" : {
			"detailColumn" : true,
			"value" : "pkTblUsuarioTienda",
			"idModal" : "detalleCliente"
		},
		"activo" : {
			"selectColumn" : true,
			"selectOptions" : [
				'Activo',
				'Inactivo'
			],
			"dadges" : true,
			"center" : true,
			"dadgesCases" : [
				{
					"text" : "Inactivo",
					"color" : "warning"
				}, {
					"text" : "Activo",
					"color" : "primary"
				}
			]
		}
	};

	protected listaClientesStatus : any = [];
	
	constructor (
		private mensajes : MensajesService,
		private apiUsuarios : UsuariosService
	) {}

	ngOnInit(): void {
		
	}

	protected cambioDeSeleccion(data: any): void {
		this.statusSeleccionados = data.selectedOptions;
	}

	protected obtenerClientesPorStatus () : void {
		this.mensajes.mensajeEsperar();
		this.obtenerClientesPorStatusFunction().then(() => {
			this.mensajes.mensajeGenericoToast('Se consultaron los Clientes por status seleccionados con éxito', 'success');
		});
	}

	private obtenerClientesPorStatusFunction () : Promise<any> {
		const arrStatus = { status : this.statusSeleccionados.map(({value}) => value) };
		return this.apiUsuarios.obtenerPedidosPorStatus(arrStatus).toPromise().then(
			respuesta => {
				this.listaClientesStatus = respuesta.data.clientes;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected limpiarTabla () : void {
		this.listaClientesStatus = [];
	}

	protected canGet() : boolean {
		return !(this.statusSeleccionados.length > 0);
	}

	protected canExport() : boolean {
		return !(this.listaClientesStatus.length > 0);
	}

	protected canClean() : boolean {
		return !(this.listaClientesStatus.length > 0);
	}
}