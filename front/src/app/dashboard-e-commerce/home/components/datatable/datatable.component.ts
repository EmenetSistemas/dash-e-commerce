import { ChangeDetectorRef, Component, EventEmitter, Input, OnChanges, OnInit, Output } from '@angular/core';
import { environment } from 'src/environments/environment';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';
import { ModificacionProductoComponent } from '../../modules/productos/modificacion-producto/modificacion-producto.component';
import { DetallePedidoComponent } from '../../modules/pedidos/detalle-pedido/detalle-pedido.component';

@Component({
	selector: 'app-datatable',
	templateUrl: './datatable.component.html',
	styleUrls: ['./datatable.component.css']
})
export class DatatableComponent implements OnInit, OnChanges {
	@Input() columnasTabla: any = [];
	@Input() datosTabla: any = [];
	@Input() tableConfig: any = [];
	@Output() selectionChange: EventEmitter<any> = new EventEmitter<any>();
	@Output() actionSelected: EventEmitter<any> = new EventEmitter<any>();

	protected selectedCheckboxes: any[] = [];

	public currentPage: number = 1;
	public itemsPerPageOptions = [5, 10, 25, 50];
	public itemsPerPage = this.itemsPerPageOptions[0];

	public sortBy: string = '';
	public sortDesc: boolean = false;

	public filterValues: { [key: string]: string } = {};

	private url = environment.api;

	constructor(
		private mensajes: MensajesService,
		private modalService: ModalService,
		private cdRef: ChangeDetectorRef
	) { }

	ngOnInit(): void {
		this.selectedCheckboxes = [];
		this.emitirDatos();
		Object.keys(this.columnasTabla).forEach((key) => {
			this.filterValues[key] = '';
		});
		this.limpiarFiltros();
	}

	ngOnChanges(): void {
		this.selectedCheckboxes = [];
		this.emitirDatos();
		this.onItemsPerPageChange();
		this.limpiarFiltros();
	}

	abrirModalModificacion(idDetalle: number, idModal: string) {
		const dataModal = {
			idDetalle: idDetalle
		};
		switch (idModal) {
			case 'modificacionProducto':
				this.modalService.abrirModalConComponente(ModificacionProductoComponent, dataModal);
				break;
		}
	}

	abrirModalDetalle(idDetalle: number, idModal: string) {
		const dataModal = {
			idDetalle: idDetalle
		};
		switch (idModal) {
			case 'detallePedido':
				this.modalService.abrirModalConComponente(DetallePedidoComponent, dataModal, ' sp-large-modal');
				break;
		}
	}

	descargarPdf(idDetalle: number, rutaPdf: string) {
		this.mensajes.mensajeEsperar();
		window.open(this.url + '/' + rutaPdf + '/' + idDetalle);
		this.mensajes.mensajeGenerico('Se generó el PDF con éxito', 'success');
	}

	private getDateDb(dateString: string): Date | null {
		const parts = dateString.split('-');
		if (parts.length === 3) {
			const day = +parts[0];
			const month = +parts[1] - 1;
			const year = +parts[2];
			return new Date(year, month, day);
		}
		return null;
	}

	private getDateInput(dateString: string): Date | null {
		const parts = dateString.split('-');
		if (parts.length === 3) {
			const day = +parts[2];
			const month = +parts[1] - 1;
			const year = +parts[0];
			return new Date(year, month, day);
		}
		return null;
	}

	get paginatedItems() {
		const startIndex = (this.currentPage - 1) * this.itemsPerPage;
		const endIndex = startIndex + this.itemsPerPage;

		return this.datosTabla.filter((registro: any) => {
			return Object.keys(this.filterValues).every((column: any) => {
				const filter = this.filterValues[column].toLowerCase();
				const value = registro[column.replace('_inicio', '').replace('_fin', '')];
		
				if (column.endsWith('_inicio') || column.endsWith('_fin')) {
					column = column.replace('_inicio', '').replace('_fin', '');
					const startDate = this.getDateInput(this.filterValues[column + '_inicio'] ?? '');
					const endDate = this.getDateInput(this.filterValues[column + '_fin'] ?? '');
					const dateValue = this.getDateDb(value ?? '');
					
					if (startDate && endDate && dateValue) {
						return dateValue >= startDate && dateValue <= endDate;
					}
		
					return true;
				}

				if (filter === '') {
					return true;
				} else if (filter === 'null' && this.tableConfig[column]?.showEmptyOption) {
					return value === undefined || value === null || value === '';
				} else {
					return value?.toString().toLowerCase().includes(filter);
				}
			});
		}).slice(startIndex, endIndex);
	}

	get totalPages() {
		return Math.ceil(this.datosTabla.length / this.itemsPerPage);
	}

	get pagesArray() {
		const visiblePages = 3;
		const halfVisible = Math.floor(visiblePages / 2);

		let startPage = Math.max(this.currentPage - halfVisible, 1);
		let endPage = startPage + visiblePages - 1;

		if (endPage > this.totalPages) {
			endPage = this.totalPages;
			startPage = Math.max(endPage - visiblePages + 1, 1);
		}

		return Array(endPage - startPage + 1).fill(0).map((_, i) => startPage + i);
	}

	goToPage(page: number) {
		if (page >= 1 && page <= this.totalPages) {
			this.currentPage = page;
		}
	}

	onItemsPerPageChange() {
		this.currentPage = 1;
		this.itemsPerPage = Number(this.itemsPerPage);
	}

	sortColumn(indice: string) {
		if (this.sortBy === indice) {
			this.sortDesc = !this.sortDesc;
		} else {
			this.sortBy = indice;
			this.sortDesc = false;
		}

		this.datosTabla.sort((a: any, b: any) => {
			const valueA = a[indice];
			const valueB = b[indice];

			if (valueA < valueB) {
				return this.sortDesc ? 1 : -1;
			} else if (valueA > valueB) {
				return this.sortDesc ? -1 : 1;
			} else {
				return 0;
			}
		});
	}

	getColumnKeys(): string[] {
		return Object.keys(this.columnasTabla);
	}

	getStartIndex(): number {
		return (this.currentPage - 1) * this.itemsPerPage + 1;
	}

	getEndIndex(): number {
		const endIndex = this.currentPage * this.itemsPerPage;
		return Math.min(endIndex, this.datosTabla.length);
	}

	isCheckboxSelected(id: number): boolean {
		return this.selectedCheckboxes.includes(id);
	}

	toggleCheckboxSelection(event: any, id: number): void {
		if (event.target.checked) {
			this.selectedCheckboxes.push(id);
		} else {
			const index = this.selectedCheckboxes.indexOf(id);
			if (index !== -1) {
				this.selectedCheckboxes.splice(index, 1);
			}
		}

		this.emitirDatos();
	}

	limpiarFiltros(): void {
		Object.keys(this.filterValues).forEach((key) => {
			this.filterValues[key] = '';
		});
		this.currentPage = 1;
		this.cdRef.detectChanges();
	}

	getTableColumnStyle(columna: string, rowData: any): any {
		const columnConfig = this.tableConfig[columna];

		if (columnConfig && columnConfig.style) {
			const cantidad = rowData[columna];

			if (cantidad != null && cantidad > 0) {
				return columnConfig.style;
			}
		}

		return null;
	}

	protected emitirDatos(): void {
		const data = {
			selectedOptions: this.selectedCheckboxes
		};
		this.selectionChange.emit(data);
	}

	protected emitirIdAccion(action: string, idAccion: number): void {
		const data = {
			action: action,
			idAccion: idAccion
		};
		this.actionSelected.emit(data);
	}
}