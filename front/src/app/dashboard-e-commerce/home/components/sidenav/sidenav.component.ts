import { Component, OnInit } from '@angular/core';
import { ModalService } from 'src/app/services/modal/modal.service';
import { CatalogoCategoriasComponent } from '../../modules/catalogos/catalogo-categorias/catalogo-categorias.component';
import { CatalogoApartadosComponent } from '../../modules/catalogos/catalogo-apartados/catalogo-apartados.component';

@Component({
	selector: 'app-sidenav',
	templateUrl: './sidenav.component.html',
	styleUrls: ['./sidenav.component.css']
})
export class SidenavComponent implements OnInit{
	constructor (
		private modalService : ModalService
	) {}

	ngOnInit(): void {
		
	}

	protected abrirModal (modal: string) : void {
		switch (modal) {
			case 'catalogoCategorias':
				this.modalService.abrirModalConComponente(CatalogoCategoriasComponent);
			break;
			case 'catalogoApartados':
				this.modalService.abrirModalConComponente(CatalogoApartadosComponent);
			break;
		}
	}
}