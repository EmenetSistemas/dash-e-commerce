import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MensajesService } from 'src/app/services/mensajes/mensajes.service';
import { LoginService } from '../services/login/login.service';
import { Router } from '@angular/router';

@Component({
	selector: 'app-login',
	templateUrl: './login.component.html',
	styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
	public formLogin!: FormGroup;

	constructor(
		private fb: FormBuilder,
		private mensajes: MensajesService,
		private apiLogin: LoginService,
		private router: Router
	) { }

	ngOnInit(): void {
		this.crearFormLogin();
	}

	crearFormLogin(): void {
		this.formLogin = this.fb.group({
			correo: ['', [Validators.email, Validators.required]],
			password: ['', [Validators.required]]
		});
	}

	login(): void {
		this.mensajes.mensajeEsperar();

		if (this.formLogin.invalid) {
			this.mensajes.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'warning');
			return;
		}

		this.apiLogin.login(this.formLogin.value).subscribe(
			respuesta => {
				if (respuesta.status != 200) {
					this.mensajes.mensajeGenerico(respuesta.mensaje, 'warning');
					return;
				}

				localStorage.setItem('token', respuesta.data.token);
				this.router.navigate(['/dashboard/inicio']);
				this.mensajes.cerrarMensajes();
			}, error => {
				this.mensajes.mensajeGenerico('error', 'error');
			}
		);
	}
}