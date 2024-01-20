import { Injectable } from '@angular/core';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';

@Injectable({
	providedIn: 'root'
})
export class ModalService {
	modalRef: BsModalRef | undefined;

	constructor(private modalService: BsModalService) { }

	abrirModalConComponente(component: any, dataModal: any = null, typeModal : string = ' custom-modal') {
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
			document.body.classList.remove('modal-open');
			document.body.style.paddingRight = '';
			document.body.style.overflow = '';
			this.modalRef.hide();
		}
	}
}