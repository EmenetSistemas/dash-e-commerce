import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
	providedIn: 'root'
})
export class ProductosService {
	private url = environment.api;

	constructor(
		private http: HttpClient
	) { }

	public obtenerProductos (datosMostrar : string) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerProductos/'+datosMostrar);
	}

	public obtenerDetalleProducto ( idProducto : number ) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerdetalleProducto/'+idProducto);
	}

	public obtenerCategoriasApartados () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerCategoriasApartados');
	}

	public modificarProducto (data : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/modificarProducto', data);
	}

	public registrarCaracteristicaProducto (caracteristica : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/registrarCaracteristicaProducto', caracteristica);
	}

	public obtenerCaracteristicasProducto (pkProducto : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerCaracteristicasProducto/'+pkProducto);
	}

	public actualizarCaracteristicaProducto (caracteristica : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/actualizarCaracteristicaProducto', caracteristica);
	}

	public eliminarCaracteristicaProducto (pkProducto : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/eliminarCaracteristicaProducto/'+pkProducto);
	}

	public obtenerStatusPedidosSelect () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/obtenerStatusPedidosSelect');
	}

	public obtenerPedidosPorStatus (status : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/pedidos/obtenerPedidosPorStatus', status);
	}

	public obtenerDetallePedido (idPedido : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/obtenerDetallePedido/'+idPedido);
	}

	public enviarPedido (idPedido : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/enviarPedido/'+idPedido);
	}

	public entregarPedido (idPedido : number) : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/entregarPedido/'+idPedido);
	}

	public actualizarFechaEstimadaEntrega (data : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/pedidos/actualizarFechaEstimadaEntrega', data);
	}

	public obtenerCantidadPedidosPendientes () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/obtenerCantidadPedidosPendientes');
	}

	public obtenerTotalesDashboard () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/pedidos/obtenerTotalesDashboard');
	}

	public obtenerProductosAgregadosRecientes () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerProductosAgregadosRecientes');
	}

	public registrarCategoriaProducto (categoria : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/registrarCategoriaProducto', categoria);
	}

	public obtenerCategoriasProductos () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerCategoriasProductos');
	}

	public actualizarCategoriaProducto (categoria : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/actualizarCategoriaProducto', categoria);
	}

	public registrarApartadpProducto (apartado : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/registrarApartadpProducto', apartado);
	}

	public obtenerApartadosProductos () : Observable<any> {
		return this.http.get<any>(this.url + '/dashboard/productos/obtenerApartadosProductos');
	}

	public actualizarApartadoProducto (categoria : any) : Observable<any> {
		return this.http.post<any>(this.url + '/dashboard/productos/actualizarApartadoProducto', categoria);
	}
}