import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AppComponent } from './app.component';
import { AppRoutes } from './app.routing';
import { RouterModule } from '@angular/router';
import { LoginComponent } from './auth/login/login.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { HomeComponent } from './dashboard-e-commerce/home/home.component';
import { NavbarComponent } from './dashboard-e-commerce/home/components/navbar/navbar.component';
import { HomeModule } from './dashboard-e-commerce/home/home.module';
import { SidenavComponent } from './dashboard-e-commerce/home/components/sidenav/sidenav.component';
import { ModalModule } from 'ngx-bootstrap/modal';
import { FooterComponent } from './dashboard-e-commerce/home/components/footer/footer.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    HomeComponent,
    NavbarComponent,
    SidenavComponent,
    FooterComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    RouterModule.forRoot(AppRoutes),
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule,
    HomeModule,
    ModalModule.forRoot()
  ],
  providers: [],
  bootstrap: [AppComponent],
  exports: [RouterModule]
})
export class AppModule { }
