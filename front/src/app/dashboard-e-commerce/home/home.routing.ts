import { Routes } from "@angular/router";
import { HomeComponent } from "./home.component";
import { InicioComponent } from "./modules/inicio/inicio.component";
import { ProductosPendientesComponent } from "./modules/productos-pendientes/productos-pendientes.component";

export const HomeRoutes: Routes = [
    {
        path: 'dashboard',
        component: HomeComponent,
        children: [
            {
                path: 'inicio',
                component: InicioComponent
            }, {
                path: 'productos/:datos',
                component: ProductosPendientesComponent
            }
        ]
    }
];