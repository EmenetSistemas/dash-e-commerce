import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService } from 'src/app/auth/services/login/login.service';
import { UsuariosService } from 'src/app/dashboard-e-commerce/services/usuarios/usuarios.service';
import { DataService } from 'src/app/services/data/data.service';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';

@Component({
	selector: 'app-navbar',
	templateUrl: './navbar.component.html',
	styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit{
	protected informacionUsuario: any = [];

	constructor(
		private dataService: DataService,
		private mensajes: MensajesService,
		private apiLogin: LoginService,
		private apiUsuarios: UsuariosService,
		private router: Router
	) { }

	ngOnInit(): void {
		this.obtenerDatosUsuarios();
	}

	prueba(): void {
		this.dataService.claseSidebar = this.dataService.claseSidebar == '' ? 'toggle-sidebar' : '';
	}

	obtenerDatosUsuarios(): void {
		let token = localStorage.getItem('token');
		if (token != undefined) {
			this.apiUsuarios.obtenerInformacionUsuarioPorToken(token).subscribe(
				respuesta => {
					this.informacionUsuario = respuesta;
				}, error => {
					localStorage.removeItem('token');
					localStorage.clear();
					this.router.navigate(['/']);
					this.mensajes.mensajeGenerico('Al parecer su sesión expiró, necesita volver a iniciar sesión', 'error');
				}
			)
		}
	}

	logout(): void {
		this.mensajes.mensajeEsperar();
		let token = localStorage.getItem('token');
		this.apiLogin.logout(token).subscribe(
			respuesta => {
				localStorage.removeItem('token');
				localStorage.clear();
				this.router.navigate(['/']);
				this.mensajes.mensajeGenerico(respuesta.mensaje, 'info');
			},

			error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}
}