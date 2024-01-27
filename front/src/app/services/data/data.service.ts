import { EventEmitter, Injectable } from '@angular/core';

@Injectable({
	providedIn: 'root'
})
export class DataService {
	public claseSidebar: string = '';
	public realizarClickConsultaPorductos: EventEmitter<void> = new EventEmitter<void>();
	public realizarClickConsultaPedidos: EventEmitter<void> = new EventEmitter<void>();
}