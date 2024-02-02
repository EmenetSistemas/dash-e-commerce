import { Injectable } from '@angular/core';
import { environment } from '../../../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
	providedIn: 'root'
})
export class UsuariosService {
	private url = environment.api;

	constructor(
		private http: HttpClient
	) { }

	public obtenerInformacionUsuarioPorToken(token: any): Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/usuarios/obtenerInformacionUsuarioPorToken', { token });
	}

	public obtenerInformacionUsuarioPorId(idUsuario: number): Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/usuarios/obtenerInformacionUsuarioPorId/'+ idUsuario);
	}

	public obtenerCantidadUsuariosTienda() : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/usuarios/obtenerCantidadUsuariosTienda');
	}

	public obtenerPedidosPorStatus(arrStatus : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/usuarios/obtenerClientesPorStatus', arrStatus);
	}

	public obtenerDetalleCliente (idUsuario : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/usuarios/obtenerDetalleCliente/'+idUsuario);
	}
}