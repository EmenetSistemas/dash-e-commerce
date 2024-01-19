import { CommonModule } from "@angular/common";
import { NgModule } from "@angular/core";
import { HomeRoutes } from "./home.routing";
import { RouterModule } from "@angular/router";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { InicioComponent } from "./modules/inicio/inicio.component";
import { ProductosPendientesComponent } from './modules/productos-pendientes/productos-pendientes.component';
import { DatatableComponent } from './components/datatable/datatable.component';

@NgModule({
    imports:[
        CommonModule,
        RouterModule.forChild(HomeRoutes),
        FormsModule,
        ReactiveFormsModule
    ],
    declarations: [
        InicioComponent,
        ProductosPendientesComponent,
        DatatableComponent
    ]
})

export class HomeModule {}