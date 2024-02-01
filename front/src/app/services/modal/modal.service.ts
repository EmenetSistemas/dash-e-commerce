import { Injectable } from '@angular/core';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';

@Injectable({
	providedIn: 'root'
})
export class ModalService {
	modalRef: BsModalRef | undefined;

	constructor(private modalService: BsModalService) { }

	abrirModalConComponente(component: any, dataModal: any = null, typeModal : string = '') {
		const modalConfig = {
			ignoreBackdropClick: true,
			keyboard: false,
			animated: true,
			initialState: dataModal,
			class: 'modal-xl modal-dialog-centered'+typeModal,
			style: {
				'background-color': 'transparent',
				'overflow-y': 'auto'
			}
		};
		this.modalRef = this.modalService.show(component, modalConfig);
	}

	cerrarModal() {
		if (this.modalRef) {
			this.modalRef.hide();
		}
		document.body.classList.remove('modal-open');
		document.body.style.paddingRight = '';
		document.body.style.overflow = '';
	}
}