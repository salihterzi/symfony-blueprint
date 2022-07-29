import { Injectable } from '@angular/core';
import {
  ActivatedRouteSnapshot,
  CanActivate,
  CanActivateChild,
  Router,
  RouterStateSnapshot,
  UrlTree
} from '@angular/router';
import { Observable } from 'rxjs';
import {AuthService} from "./auth.service";

@Injectable({
  providedIn: 'root'
})
export class PermissionGuard implements CanActivate,  CanActivateChild {
  constructor(private authService: AuthService, private router: Router) {

  }

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): boolean {
    const permissions = route.data['permissions'] ? route.data['permissions'] as Array<string> : [];
    let param = route.data['permissionParam'] ? route.data['permissionParam'] : null;

   if (this.authService.hasPermissions(permissions,param)) {
      return true;
    } else {
      this.router.navigateByUrl('/');
      return false;
    }
  }

  canActivateChild(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    return this.canActivate(route, state);
  }
}
