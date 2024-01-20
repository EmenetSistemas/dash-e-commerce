import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
	selector: 'app-modificacion-producto',
	templateUrl: './modificacion-producto.component.html',
	styleUrls: ['./modificacion-producto.component.css']
})
export class ModificacionProductoComponent extends FGenerico implements OnInit{
	@Input() idDetalle: number = 0;

	protected formProducto! : FormGroup;

	protected detalleProducto : any;
	protected apartados : any = [];
	protected categoriasApartados : any = [];
	protected imagenSeleccionada: File | any = null;

	protected columnasCaracteristicas : any = {
		'titulo' 		: 'Título',
		'descripcion' 	: 'Descripción'
	};
	protected tableConfig : any = {
		"titulo" : {
			"detailColumn" : true
		}
	};
	protected listaCaracteristicas : any[] = [];

	constructor (
		private modalService : ModalService,
		private mensajes : MensajesService,
		private apiProductos : ProductosService,
		private fb : FormBuilder
	) {
		super();
	}

	async ngOnInit () : Promise<void> {
		this.mensajes.mensajeEsperar();
		this.crearFormProducto();
		await Promise.all([
			this.obtenerDetalleProducto(),
			this.obtenerCategoriasApartados()
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
			descripcionProducto: [{ value: null, disabled: true }, []],
			tituloCaracteristica : [null, []],
			descripcionCaracteristica : [null, []]
		});
	}
	
	private obtenerCategoriasApartados () : Promise<any> {
		return this.apiProductos.obtenerCategoriasApartados().toPromise().then(
			respuesta => {
				this.categoriasApartados = respuesta.data.categoriasApartados;
				this.apartados = respuesta.data.categoriasApartados.flatMap((categoria : any) => categoria.apartados);
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
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

	protected cambioApartado () : void {
		const pkApartado = this.formProducto.get('apartadoProducto')?.value;
		const fkCategoria = this.apartados.find((apartado : any) => apartado.id == pkApartado).fkCatCategoria;

		this.formProducto.get('categoriaProducto')?.setValue(fkCategoria);
	}

	protected onFileChange(event: Event): void {
		const inputElement = event.target as HTMLInputElement;
		this.imagenSeleccionada = (inputElement.files && inputElement.files.length > 0) ? inputElement.files[0] : null;
	}

	protected urlImagen () : string {
		return URL.createObjectURL(this.imagenSeleccionada);
	}

	protected agregarCaracteristica () : void {
		const titulo = this.formProducto.get('tituloCaracteristica')?.value;
		const descripcion = this.formProducto.get('descripcionCaracteristica')?.value;

		if (this.is_empty(titulo) || this.is_empty(descripcion)) {
			this.mensajes.mensajeGenerico('Se debe colocar un título y su respectiva descripción para agregar una característica', 'warning', 'Agregar característica');
			return;
		}

		this.listaCaracteristicas = [
			...this.listaCaracteristicas,
			{
				titulo : titulo,
				descripcion : descripcion
			}
		];

		this.formProducto.get('tituloCaracteristica')?.setValue(null);
		this.formProducto.get('descripcionCaracteristica')?.setValue(null);

		this.mensajes.mensajeGenericoToast('Se agregó la característica con éxito', 'success');
	}

	protected cancelarModificacion () : void {
		this.cerrarModal();
	}

	private cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}