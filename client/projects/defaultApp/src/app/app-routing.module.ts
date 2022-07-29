import {NgModule} from '@angular/core';
import { RouterModule, Route} from '@angular/router';
import {WelcomeComponent} from './welcome/welcome.component';
import {AuthGuard} from './shared/auth/auth.guard';

export interface UiRouteData {
  [name: string]: any;
  title?: string;
  permissions?: string[];
  permissionParam?: any;
}

export interface UiRoute extends Route {
  data?: UiRouteData;
  children?: UiRoutes;
}
export declare type UiRoutes = UiRoute[];

const routes: UiRoutes = [
  {
    path: '',
    component: WelcomeComponent,
  },
  {
    path: 'user',
    loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule),
  },
  {
    path: 'admin',
    canActivate: [AuthGuard],
    loadChildren: () => import('./admin/admin.module').then(m => m.AdminModule),
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
