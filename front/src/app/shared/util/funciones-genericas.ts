import { DatePipe } from '@angular/common';
import { Component } from '@angular/core';
import * as moment from 'moment';

@Component({template: ''})

export default class FGenerico {
    public soloLetras(event: KeyboardEvent) {
        const pattern = /[a-zA-Zá-úÁ-Ú ]/;
        const inputChar = String.fromCharCode(event.charCode);
    
        if (!pattern.test(inputChar)) {
            event.preventDefault();
        }
    }
    
    public soloTexto(event: KeyboardEvent) {
        const pattern = /[a-zA-Zá-úÁ-Ú0-9 .,-@#$%&+*[{}()?¿!¡]/;
        const inputChar = String.fromCharCode(event.charCode);
    
        if (!pattern.test(inputChar)) {
            event.preventDefault();
        }
    }
    
    public soloNumeros(event: KeyboardEvent) {
        const pattern = /[0-9]/;
        const inputChar = String.fromCharCode(event.charCode);
    
        if (!pattern.test(inputChar)) {
            event.preventDefault();
        }
    }

    public is_empty(cadena : string) {
        return  cadena == null || cadena == undefined || cadena.trim() == '';
    }

    getNowString(): any {
		const hoy = Date.now();

        return moment(hoy).format("YYYY-MM-DD hh:mm A");
	}
}