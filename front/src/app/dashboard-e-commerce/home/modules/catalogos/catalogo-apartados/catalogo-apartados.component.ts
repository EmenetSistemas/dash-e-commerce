import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductosService } from 'src/app/dashboard-e-commerce/services/productos/productos.service';
import FGenerico from 'src/app/dashboard-e-commerce/shared/util/funciones-genericas';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { ModalService } from 'src/app/services/modal/modal.service';

@Component({
  selector: 'app-catalogo-apartados',
  templateUrl: './catalogo-apartados.component.html',
  styleUrls: ['./catalogo-apartados.component.css']
})
export class CatalogoApartadosComponent extends FGenerico implements OnInit{
	protected formApartado! : FormGroup;

	protected columnasCategorias : any = {
		'nombre' 	  	  : 'Nombre',
		'descripcion' 	  : 'Descripción',
		'nombreCategoria' : 'Categoría',
		'actions' 	  	  : 'Acciones'
	};

	protected tableConfig : any = {
		"nombreCategoria" : {
			"selectColumn" : true,
			"selectOptions" : []
		},
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

	protected categorias : any = [];
	protected listaApartados : any[] = [];

	private idCaracteristicaMod : number = 0;
	protected mostrarUpdate : boolean = false;

	constructor (
		private modalService : ModalService,
		private fb : FormBuilder,
		private apiProductos : ProductosService,
		private mensajes : MensajesService
	) {
		super();
	}

	async ngOnInit() : Promise<void> {
		this.mensajes.mensajeEsperar();
		this.crearFormApartado();
		await Promise.all([
			this.obtenerCategoriasApartados(),
			this.obtenerApartadosProductos()
		]);
		this.mensajes.cerrarMensajes();
	}

	private crearFormApartado() : void {
		this.formApartado = this.fb.group({
			nombreApartado	  	: [null, [Validators.required]],
			descripcionApartado : [null, []],
			categoria 			: ['', [Validators.required]]
		});
	}

	private obtenerCategoriasApartados () : Promise<any> {
		return this.apiProductos.obtenerCategoriasApartados().toPromise().then(
			respuesta => {
				this.categorias = respuesta.data.categoriasApartados;
				this.tableConfig.nombreCategoria.selectOptions = this.categorias.map((item : any) => item.nombre);
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	private obtenerApartadosProductos () : Promise<any> {
		return this.apiProductos.obtenerApartadosProductos().toPromise().then(
			respuesta => {
				this.listaApartados = respuesta.data.apartadosProductos;
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		)
	}

	protected registrarApartadoProducto () : void {
		const nombre = this.formApartado.get('nombreApartado')?.value;

		if (this.formApartado.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning', 'Los campos requeridos están marcados con un *');
			return;
		}

		const validaApartado = this.listaApartados.filter(apartado => apartado.nombre.replace(/\s+/g, '').toLowerCase() == nombre.replace(/\s+/g, '').toLowerCase());
		if (validaApartado.length > 0) {
			this.limpiarForm();
			this.mensajes.mensajeGenerico('Al parecer ya existe un apartado con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Apartado existente');
			return;
		}
		
		this.mensajes.mensajeEsperar();

		const apartado = {
			nombre 		: nombre,
			descripcion : this.formApartado.get('descripcionApartado')?.value,
			categoria 	: this.formApartado.get('categoria')?.value
		};

		this.apiProductos.registrarApartadpProducto(apartado).subscribe(
			respuesta => {
				this.obtenerApartadosProductos().then(() => {
					this.limpiarForm();

					this.mensajes.mensajeGenericoToast(respuesta.mensaje, 'success');
				});
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}

	protected actualizarApartadoProducto () : void {
		const nombre = this.formApartado.get('nombreApartado')?.value;

		if (this.is_empty(nombre)) {
			this.mensajes.mensajeGenerico('Se debe colocar un nombre para modificar el apartado', 'warning', 'Modificar apartado');
			return;
		}

		const validaApartado = this.listaApartados.filter(apartado => apartado.nombre.replace(/\s+/g, '').toLowerCase() == nombre.replace(/\s+/g, '').toLowerCase() && apartado.pkCatCategoria != this.idCaracteristicaMod);
		if (validaApartado.length > 0) {
			this.mensajes.mensajeGenerico('Al parecer ya existe un apartado con el mismo nombre, se debe ocupar uno diferente', 'warning', 'Apartado existente');
			return;
		}

		this.mensajes.mensajeConfirmacionCustom('Está por actualizar una categoría ¿Desea continar con la acción?', 'question', 'Actualizar categoría').then(
			respuesta => {
				if (respuesta.isConfirmed) {
					this.mensajes.mensajeEsperar();
					const apartado = {
						id 			: this.idCaracteristicaMod,
						nombre 		: nombre,
						descripcion : this.formApartado.get('descripcionApartado')?.value,
						apartado 	: this.formApartado.get('categoria')?.value
					};

					this.apiProductos.actualizarApartadoProducto(apartado).subscribe(
						respuesta => {
							this.obtenerApartadosProductos().then(() => {
								this.ocultarModificacionApartado();
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

	protected ocultarModificacionApartado () : void {
		this.mostrarUpdate = false;
		this.limpiarForm();
	}

	protected realizarAccion (data : any) : void {
		switch (data.action) {
			case 'update':
				this.idCaracteristicaMod = data.idAccion;
				const dataActualizar = this.listaApartados.find(categoria => categoria.pkCatCategoria == data.idAccion);
				this.mostrarUpdate = true;
				this.formApartado.get('nombreApartado')?.setValue(dataActualizar.nombre);
				this.formApartado.get('descripcionApartado')?.setValue(dataActualizar.descripcion);
				this.formApartado.get('categoria')?.setValue(dataActualizar.pkCatCategoria);
			break;
		}
	}

	private limpiarForm () : void {
		this.formApartado.get('nombreApartado')?.setValue(null);
		this.formApartado.get('descripcionApartado')?.setValue(null);
		this.formApartado.get('categoria')?.setValue('');
	}

	protected cerrarModal () : void {
		this.modalService.cerrarModal();
	}
}