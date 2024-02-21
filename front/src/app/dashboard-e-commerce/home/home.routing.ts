import { Routes } from "@angular/router";
import { HomeComponent } from "./home.component";
import { InicioComponent } from "./modules/inicio/inicio.component";
import { PedidosComponent } from "./modules/pedidos/consulta-pedidos/consulta-pedidos.component";
import { ConsultaProductosComponent } from "./modules/productos/consulta-productos/consulta-productos.component";
import { ConsultaClientesComponent } from "./modules/clientes/consulta-clientes/consulta-clientes.component";
import { AdminGuard } from "../guards/admin.guard";
import { ConsultaPlanesComponent } from "./modules/planes-internet/consulta-planes/consulta-planes.component";

export const HomeRoutes: Routes = [
    {
        path: 'dashboard',
        component: HomeComponent,
        canActivate : [AdminGuard],
        children: [
            {
                path: 'inicio',
                component: InicioComponent
            }, {
                path: 'productos/:datos',
                component: ConsultaProductosComponent
            }, {
                path: 'clientes',
                component: ConsultaClientesComponent
            }, {
                path: 'pedidos',
                component: PedidosComponent
            }, {
                path: 'planesInternet',
                component: ConsultaPlanesComponent
            }
        ]
    }
];