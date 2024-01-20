import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
	selector: 'app-modificacion-producto',
	templateUrl: './modificacion-producto.component.html',
	styleUrls: ['./modificacion-producto.component.css']
})
export class ModificacionProductoComponent implements OnInit{
	@Input() idDetalle: number = 0;

	protected formProducto! : FormGroup;

	protected detalleProducto : any;

	constructor (
		private modalService : ModalService,
		private mensajes : MensajesService,
		private apiProductos : ProductosService,
		private fb : FormBuilder
	) {}

	async ngOnInit () : Promise<void> {
		this.mensajes.mensajeEsperar();
		this.crearFormProducto();
		await Promise.all([
			this.obtenerDetalleProducto()
		]);
		this.mensajes.cerrarMensajes();
	}

	private crearFormProducto(): void {
		this.formProducto = this.fb.group({
			nombreProducto: [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú ]*')]],
			stockProducto: [{ value: null, disabled: true }, []],
			precioProducto: [{ value: null, disabled: true }, []],
			descuento: [null, [Validators.pattern('[0-9]*')]],
			apartadoProducto: ['', []],
			categoriaProducto: [{ value: '', disabled: true }, []],
			descripcionProducto: [{ value: null, disabled: true }, []]
		});
	}	

	private obtenerDetalleProducto () : Promise<any> {
		return this.apiProductos.obtenerDetalleProducto(this.idDetalle).toPromise().then(
			respuesta => {
				this.detalleProducto = respuesta.data.detalleProducto[0];
				this.cargarFormProducto();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFormProducto () : void {
		this.formProducto.get('nombreProducto')?.setValue(this.detalleProducto.nombre);
		this.formProducto.get('stockProducto')?.setValue(this.detalleProducto.stock);
		this.formProducto.get('descuento')?.setValue(this.detalleProducto.descuento);
		this.formProducto.get('descripcionProducto')?.setValue(this.detalleProducto.descripcion);
	}

	protected cancelarModificacion () : void {
		this.cerrarModal();
	}

	private cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}