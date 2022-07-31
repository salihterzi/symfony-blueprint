import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {UiRoutes} from "../app-routing.module";
import {AuthReverseGuard} from "../shared/auth/auth-reverse.guard";

const routes: UiRoutes = [
  {
    path: 'login',
    canActivate:[AuthReverseGuard],
    component: LoginComponent
  }
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule { }
