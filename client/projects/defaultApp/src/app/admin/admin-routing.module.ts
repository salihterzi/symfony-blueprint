import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';
import {DashboardComponent} from './dashboard/dashboard.component';
import {PageComponent} from '../shared/layout/page/page.component';
import {TestComponent} from "./test/test.component";
import {UiRoutes} from "../app-routing.module";
import {PermissionGuard} from "../shared/auth/permission.guard";


const routes: UiRoutes = [
  {
    path: '',
    component: PageComponent,
    canActivateChild: [PermissionGuard],
    children: [
      {
        path: 'dashboard',
        component: DashboardComponent
      },
      {
        path: 'test',
        component: TestComponent,
        data: {permissions: ['CAN_TEST_PERMISSION']}
      }
    ]
  },

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule {
}
