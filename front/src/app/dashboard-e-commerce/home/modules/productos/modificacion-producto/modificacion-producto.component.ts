import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { DomSanitizer } from '@angular/platform-browser';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { DataService } from 'src/app/services/data/data.service';
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
	protected imagenSeleccionada: any = null;

	protected mostrarUpdate : boolean = false;
	private idCaracteristicaMod : number = 0;

	protected columnasCaracteristicas : any = {
		'titulo' 		: 'Título',
		'descripcion' 	: 'Descripción',
		'actions' 		: 'Acciones'
	};
	protected tableConfig : any = {
		"actions" : {
			"noFilter" : true,
			"actionFilter" : true,
			"actions" : [
				{
					"nombre" : 'update',
					"titulo" : 'Editar',
					"icon" : 'bi-exclamation-triangle',
					"bg" : 'warning'
				}, {
					"nombre" : 'delete',
					"titulo" : 'Eliminar',
					"icon" : 'bi-exclamation-octagon',
					"bg" : 'danger'
				}
			],
			"value" : "id"
		}
	};
	protected listaCaracteristicas : any[] = [];

	constructor (
		private modalService : ModalService,
		private mensajes : MensajesService,
		private apiProductos : ProductosService,
		private fb : FormBuilder,
		private sanitizer: DomSanitizer,
		private dataService : DataService
	) {
		super();
	}

	async ngOnInit () : Promise<void> {
		this.mensajes.mensajeEsperar();
		this.crearFormProducto();
		await Promise.all([
			this.obtenerCategoriasApartados(),
			this.obtenerDetalleProducto(),
			this.obtenerCaracteristicasProducto()

		]);
		this.mensajes.cerrarMensajes();
	}

	private crearFormProducto(): void {
		this.formProducto = this.fb.group({
			nombreProducto			  : [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+{}()?¿!¡]*')]],
			stockProducto			  : [{ value: null, disabled: true }, []],
			precioProducto			  : [{ value: null, disabled: true }, []],
			descuento				  : [null, [Validators.pattern('[0-9]*')]],
			apartadoProducto		  : ['', [Validators.required]],
			categoriaProducto		  : [{ value: '', disabled: true }, []],
			imagenProducto			  : [],
			descripcionProducto		  : [{ value: null, disabled: true }, []],
			tituloCaracteristica	  : [null, []],
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

	private async obtenerCaracteristicasProducto () : Promise<void> {
		return this.apiProductos.obtenerCaracteristicasProducto(this.idDetalle).toPromise().then(
			respuesta => {
				this.listaCaracteristicas = respuesta.data.caracteristicasProducto;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private cargarFormProducto () : void {
		this.formProducto.get('nombreProducto')?.setValue(this.detalleProducto.nombre);
		this.formProducto.get('stockProducto')?.setValue(this.detalleProducto.stock);
		this.formProducto.get('descuento')?.setValue(this.detalleProducto.descuento);
		this.formProducto.get('apartadoProducto')?.setValue(this.detalleProducto.idApartado ?? '');
		this.cambioApartado();
		this.formProducto.get('descripcionProducto')?.setValue(this.detalleProducto.descripcion);
		this.urlImagen(this.detalleProducto.imagen);
	}

	protected cambioApartado () : void {
		const pkApartado = this.formProducto.get('apartadoProducto')?.value;
		if (pkApartado == '') return;
		const fkCategoria = this.apartados.find((apartado : any) => apartado.id == pkApartado).fkCatCategoria;

		this.formProducto.get('categoriaProducto')?.setValue(fkCategoria);
	}

	protected onFileChange(event: Event): void {
		const inputElement = event.target as HTMLInputElement;
		const file : any = (inputElement.files && inputElement.files.length > 0) ? inputElement.files[0] : null;
		if (file) {
			const reader = new FileReader();
		
			reader.onloadend = () => {
			  	const base64Image = reader.result as string;
				this.formProducto.value.imagen = base64Image;
			  	this.urlImagen(base64Image);
			};

			reader.readAsDataURL(file);
		} else {
			this.imagenSeleccionada = null;
		}
	}
	
	protected urlImagen( img64 : string ) : void {
		if (img64 != null) {
			this.imagenSeleccionada = this.sanitizer.bypassSecurityTrustUrl(img64);
		}
	}

	protected modificarProducto () : void {
		if (this.formProducto.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning', 'Los campos requeridos están marcados con un *');
			return;
		}

		if (this.imagenSeleccionada == null) {
			this.mensajes.mensajeGenerico('Se debe colocar una imagen respectiva del producto para poder continuar.', 'warning', 'Imagen producto');
			return;
		}

		if (this.listaCaracteristicas.length == 0) {
			this.mensajes.mensajeGenerico('Se debe registrar al menos una característica del producto.', 'warning', 'Características producto');
			return;
		}

		this.mensajes.mensajeEsperar();
		this.formProducto.value.pkProducto = this.idDetalle;

		this.apiProductos.modificarProducto(this.formProducto.value).subscribe(
			respuesta => {
				this.cancelarModificacion();
				this.dataService.realizarClickConsultaPorductos.emit();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected registrarCaracteristicaProducto () : void {
		const titulo = this.formProducto.get('tituloCaracteristica')?.value;
		const descripcion = this.formProducto.get('descripcionCaracteristica')?.value;

		if (this.is_empty(titulo) || this.is_empty(descripcion)) {
			this.mensajes.mensajeGenerico('Se debe colocar un título y su respectiva descripción para agregar una característica', 'warning', 'Agregar característica');
			return;
		}

		const validaCaract = this.listaCaracteristicas.filter(caract => caract.titulo.replace(/\s+/g, '').toLowerCase() == titulo.replace(/\s+/g, '').toLowerCase());
		if (validaCaract.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer ya existe una característica con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Característica existente');
			return;
		}
		
		this.mensajes.mensajeEsperar();

		const caracteristica = {
			fkTblProducto : this.idDetalle,
			titulo : titulo,
			descripcion : descripcion
		};

		this.apiProductos.registrarCaracteristicaProducto(caracteristica).subscribe(
			respuesta => {
				this.obtenerCaracteristicasProducto().then(() => {
					this.formProducto.get('tituloCaracteristica')?.setValue(null);
					this.formProducto.get('descripcionCaracteristica')?.setValue(null);

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected realizarAccion (data : any) : void {
		switch (data.action) {
			case 'update':
				this.idCaracteristicaMod = data.idAccion;
				const dataActualizar = this.listaCaracteristicas.find(caracteristica => caracteristica.id == data.idAccion);
				this.mostrarUpdate = true;
				this.formProducto.get('tituloCaracteristica')?.setValue(dataActualizar.titulo);
				this.formProducto.get('descripcionCaracteristica')?.setValue(dataActualizar.descripcion);
			break;
			case 'delete':
				this.mensajes.mensajeConfirmacionCustom('Está por elminiar una característica del producto ¿Desea continar con la acción?', 'question', 'Eliminar característica').then(
					respuesta => {
						if (respuesta.isConfirmed) {
							this.mensajes.mensajeEsperar();

							this.apiProductos.eliminarCaracteristicaProducto(data.idAccion).subscribe(
								respuesta => {
									this.obtenerCaracteristicasProducto().then(() => {
										this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
									});
								}, error => {
									this.mensajes.mensajeGenerico('error', 'error');
								}
							);
						}
					}
				);
			break;
		}
	}

	protected actualizarCaracteristicaProducto () {
		const titulo = this.formProducto.get('tituloCaracteristica')?.value;
		const descripcion = this.formProducto.get('descripcionCaracteristica')?.value;

		if (this.is_empty(titulo) || this.is_empty(descripcion)) {
			this.mensajes.mensajeGenerico('Se debe colocar un título y su respectiva descripción para modificar la característica', 'warning', 'Modificar característica');
			return;
		}

		const validaCaract = this.listaCaracteristicas.filter(caract => caract.titulo.replace(/\s+/g, '').toLowerCase() == titulo.replace(/\s+/g, '').toLowerCase() && caract.id != this.idCaracteristicaMod);
		if (validaCaract.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer ya existe una característica con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Característica existente');
			return;
		}

		this.mensajes.mensajeConfirmacionCustom('Está por actualizar una característica ¿Desea continar con la acción?', 'question', 'Actualizar característica').then(
			respuesta => {
				if (respuesta.isConfirmed) {
					this.mensajes.mensajeEsperar();
					const caracteristicaUpdate = {
						id : this.idCaracteristicaMod,
						titulo : titulo,
						descripcion : descripcion
					};

					this.apiProductos.actualizarCaracteristicaProducto(caracteristicaUpdate).subscribe(
						respuesta => {
							this.obtenerCaracteristicasProducto().then(() => {
								this.ocultarModificacionCaracteristica();
								this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
							});
						}, error => {
							this.mensajes.mensajeGenerico('error', 'error');
						}
					);
					return;
				}
			}
		);
	}

	protected ocultarModificacionCaracteristica () {
		this.mostrarUpdate = false;
		this.formProducto.get('tituloCaracteristica')?.setValue(null);
		this.formProducto.get('descripcionCaracteristica')?.setValue(null);
	}

	protected cancelarModificacion () : void {
		this.formProducto.reset();
		this.cerrarModal();
	}

	private cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}