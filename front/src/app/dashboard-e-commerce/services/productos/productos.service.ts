import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
	providedIn: 'root'
})
export class ProductosService {
	private url = environment.api;
	private url_server = environment.api_server;

	constructor(
		private http: HttpClient
	) { }

	public obtenerProductosServidor(): Observable<any> {
		return this.http.get<any>(this.url_server + '/obtenerProductosServidor');
	}
}