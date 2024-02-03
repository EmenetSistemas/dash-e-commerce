import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
	selector: 'app-catalogo-categorias',
	templateUrl: './catalogo-categorias.component.html',
	styleUrls: ['./catalogo-categorias.component.css']
})
export class CatalogoCategoriasComponent extends FGenerico implements OnInit{
	protected formCategoria! : FormGroup;

	protected columnasCategorias : any = {
		'nombre' 	  : 'Nombre',
		'descripcion' : 'Descripción',
		'actions' 	  : 'Acciones'
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
				}
			],
			"value" : "pkCatCategoria"
		}
	};

	protected listaCategorias : any[] = [];

	private idCaracteristicaMod : number = 0;
	protected mostrarUpdate : boolean = false;

	constructor(
		private modalService : ModalService,
		private fb : FormBuilder,
		private apiProductos : ProductosService,
		private mensajes : MensajesService
	) {
		super();
	}

	async ngOnInit() : Promise<void> {
		this.mensajes.mensajeEsperar();
		this.crearFormCategoria();
		await this.obtenerCategoriasProductos();
		this.mensajes.cerrarMensajes();
	}

	private crearFormCategoria(): void {
		this.formCategoria = this.fb.group({
			nombreCategoria	  : [null, []],
			descripcionCategoria : [null, []]
		});
	}

	private obtenerCategoriasProductos () : Promise<any> {
		return this.apiProductos.obtenerCategoriasProductos().toPromise().then(
			respuesta => {
				this.listaCategorias = respuesta.data.categoriasProductos;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		)
	}

	protected registrarCategoriaProducto () : void {
		const nombre = this.formCategoria.get('nombreCategoria')?.value;
		const descripcion = this.formCategoria.get('descripcionCategoria')?.value;

		if (this.is_empty(nombre)) {
			this.mensajes.mensajeGenerico('Se debe colocar un nombre para agregar una categoría', 'warning', 'Agregar categoría');
			return;
		}

		const validaCategoria = this.listaCategorias.filter(caract => caract.nombre.replace(/\s+/g, '').toLowerCase() == nombre.replace(/\s+/g, '').toLowerCase());
		if (validaCategoria.length > 0) {
			this.limpiarForm();
			this.mensajes.mensajeGenerico('Al parecer ya existe una categoría con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Categoría existente');
			return;
		}
		
		this.mensajes.mensajeEsperar();

		const categoria = {
			nombre : nombre,
			descripcion : descripcion
		};

		this.apiProductos.registrarCategoriaProducto(categoria).subscribe(
			respuesta => {
				this.obtenerCategoriasProductos().then(() => {
					this.limpiarForm();

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected actualizarCategoriaProducto () : void {
		const nombre = this.formCategoria.get('nombreCategoria')?.value;
		const descripcion = this.formCategoria.get('descripcionCategoria')?.value;

		if (this.is_empty(nombre)) {
			this.mensajes.mensajeGenerico('Se debe colocar un nombre para modificar la categoría', 'warning', 'Modificar categoría');
			return;
		}

		const validaCategoria = this.listaCategorias.filter(categoria => categoria.nombre.replace(/\s+/g, '').toLowerCase() == nombre.replace(/\s+/g, '').toLowerCase() && categoria.pkCatCategoria != this.idCaracteristicaMod);
		if (validaCategoria.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer ya existe una categoría con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Categoría existente');
			return;
		}

		this.mensajes.mensajeConfirmacionCustom('Está por actualizar una categoría ¿Desea continar con la acción?', 'question', 'Actualizar categoría').then(
			respuesta => {
				if (respuesta.isConfirmed) {
					this.mensajes.mensajeEsperar();
					const categoria = {
						id : this.idCaracteristicaMod,
						nombre : nombre,
						descripcion : descripcion
					};

					this.apiProductos.actualizarCategoriaProducto(categoria).subscribe(
						respuesta => {
							this.obtenerCategoriasProductos().then(() => {
								this.ocultarModificacionCategoria();
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

	protected realizarAccion (data : any) : void {
		switch (data.action) {
			case 'update':
				this.idCaracteristicaMod = data.idAccion;
				const dataActualizar = this.listaCategorias.find(categoria => categoria.pkCatCategoria == data.idAccion);
				this.mostrarUpdate = true;
				this.formCategoria.get('nombreCategoria')?.setValue(dataActualizar.nombre);
				this.formCategoria.get('descripcionCategoria')?.setValue(dataActualizar.descripcion);
			break;
		}
	}

	protected ocultarModificacionCategoria () : void {
		this.mostrarUpdate = false;
		this.limpiarForm();
	}

	private limpiarForm () : void {
		this.formCategoria.get('nombreCategoria')?.setValue(null);
		this.formCategoria.get('descripcionCategoria')?.setValue(null);
	}

	public cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}