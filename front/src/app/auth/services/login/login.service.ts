import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  private url = environment.api;

  constructor(
    private http : HttpClient
  ) { }

  public login(credenciales : any) : Observable<any> {
    return this.http.post<any>(this.url+'/auth/login', credenciales);
  }

  public auth(token : any) : Observable<any> {
    return this.http.post<any>(this.url+'/auth', {token});
  }

  public logout(token : any) : Observable<any> {
    return this.http.post<any>(this.url+'/logout',{token});
  }
}
