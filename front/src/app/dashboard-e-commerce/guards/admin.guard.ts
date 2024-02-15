import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, NavigationStart, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { LoginService } from 'src/app/auth/services/login/login.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Injectable({
  providedIn: 'root'
})
export class AdminGuard implements CanActivate {
  private previousUrl: string = '/logistica/inicio';

  private exepcionesRutas : string[] = [
    'inicio'
  ];

  constructor (
    private router : Router,
    private mensajes : MensajesService,
    private apiLogin : LoginService
  ) {
    this.router.events.subscribe(event => {
      if (event instanceof NavigationStart) {
        this.validarToken(event.url);
      }
    });
  }

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
      const url = state.url;
      return this.validarToken(url);
  }
  
  validarToken(url: string): any {
    if (url === '/') {
      return true;
    }

    const token = localStorage.getItem('token');
    const cadenaPermisos = localStorage.getItem('permisos');

    if (
      ( token == undefined || token == null ) &&
      ( cadenaPermisos == undefined || cadenaPermisos == null )
    ) {
      localStorage.removeItem('token');
      localStorage.clear();
      this.router.navigate(['/']);
      this.mensajes.mensajeGenerico('Para navegar dentro del Administrador de la tienda se necesita inicar sesión antes', 'info');
      return false;
    }

    this.apiLogin.auth(token).subscribe(
      status => {
        if (status) {
          return true;
        } else {
          localStorage.removeItem('token');
          localStorage.clear();
          this.router.navigate(['/']);
          this.mensajes.mensajeGenerico('Al parecer su sesión expiró, necesita volver a iniciar sesión', 'error');
          return false;
        }
      },
      error => {
        localStorage.removeItem('token');
        localStorage.clear();
        this.router.navigate(['/']);
        this.mensajes.mensajeGenerico('error', 'error');
        return false;
      }
    );
  }
}
