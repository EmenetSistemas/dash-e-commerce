import { CommonModule } from "@angular/common";
import { NgModule } from "@angular/core";
import { HomeRoutes } from "./home.routing";
import { RouterModule } from "@angular/router";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { InicioComponent } from "./modules/inicio/inicio.component";
import { DatatableComponent } from './components/datatable/datatable.component';
import { ConsultaProductosComponent } from './modules/productos/consulta-productos/consulta-productos.component';
import { ModificacionProductoComponent } from './modules/productos/modificacion-producto/modificacion-producto.component';
import { ModalModule } from "ngx-bootstrap/modal";
import { PedidosComponent } from "./modules/pedidos/consulta-pedidos/consulta-pedidos.component";
import { DropdownComponent } from "./components/dropdown/dropdown.component";
import { DetallePedidoComponent } from "./modules/pedidos/detalle-pedido/detalle-pedido.component";
import { ConsultaClientesComponent } from './modules/clientes/consulta-clientes/consulta-clientes.component';
import { DetalleClienteComponent } from './modules/clientes/detalle-cliente/detalle-cliente.component';

@NgModule({
    imports:[
        CommonModule,
        RouterModule.forChild(HomeRoutes),
        FormsModule,
        ReactiveFormsModule,
        ModalModule.forChild()
    ],
    declarations: [
        InicioComponent,
        DatatableComponent,
        ConsultaProductosComponent,
        ModificacionProductoComponent,
        PedidosComponent,
        DropdownComponent,
        DetallePedidoComponent,
        ConsultaClientesComponent,
        DetalleClienteComponent
    ]
})

export class HomeModule {}