import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
	providedIn: 'root'
})
export class PlanesInternetService {
	private url = environment.api;

	constructor(
		private http: HttpClient
	) {}

	public obtenerPlanesInternet () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/planes-internet/obtenerPlanesInternet');
	}

	public obtenerCaracteristicasPlanes () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/planes-internet/obtenerCaracteristicasPlanes');
	}

	public obtenerDetallePlan (idPlan : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/planes-internet/obtenerDetallePlan/'+idPlan);
	}

	public registrarPlan (data : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/planes-internet/registrarPlan', data)
	}

	public modificarPlan (data : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/planes-internet/modificarPlan', data)
	}

	public registrarCaracteristicaPlan (caracteristica : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/planes-internet/registrarCaracteristicaPlan', caracteristica)
	}

	public actualizarCaracteristicaPlan (caracteristica : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/planes-internet/actualizarCaracteristicaPlan', caracteristica)
	}

	public eliminarCaracteristicaPlan (idCaracteristica : number) : Observable<any> {
		return this.http.get<any>(`${this.url}/dashboard/planes-internet/eliminarCaracteristicaPlan/${idCaracteristica}`);
	}
}