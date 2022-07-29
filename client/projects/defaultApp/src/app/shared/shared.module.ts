import {APP_INITIALIZER, NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {NavbarComponent} from './layout/navbar/navbar.component';
import {FooterComponent} from './layout/footer/footer.component';
import {PageComponent} from './layout/page/page.component';
import {RouterModule} from '@angular/router';
import {AuthService} from './auth/auth.service';
import { PermissionDirective } from './auth/permission.directive';

export function initApp(initService: AuthService) {
  return () => initService.init();
}
@NgModule({
  declarations: [NavbarComponent, FooterComponent, PageComponent, PermissionDirective],
    exports: [
        PageComponent,
        NavbarComponent,
        FooterComponent,
        PermissionDirective
    ],
  imports: [
    CommonModule,
    RouterModule

  ]
})
export class SharedModule {
  static forRoot() {
    return {
      ngModule: SharedModule,
      providers: [
        AuthService,
        {
          provide: APP_INITIALIZER, useFactory: initApp, deps: [AuthService], multi: true
        },
      ]
    };
  }
}
