import { Routes } from "@angular/router";
import { LoginComponent } from "./auth/login/login.component";
import { HomeComponent } from "./dashboard-e-commerce/home/home.component";
import { InvalidRouteComponent } from "./shared/invalid-route/invalid-route.component";

export const AppRoutes: Routes = [
    {
        path : '',
        component : LoginComponent
    }, {
        path : 'daschboard',
        component : HomeComponent
    }, {
        path : '**',
        component : InvalidRouteComponent
    }
];