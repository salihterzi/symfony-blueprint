import {Component, OnInit} from '@angular/core';
import {AuthService} from "../../auth/auth.service";
import {User} from "../../auth/user.model";
import {Router} from "@angular/router";

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {
  user: User;
  authenticated: boolean;

  constructor(private authService: AuthService, private router: Router) {
  }

  ngOnInit(): void {
    this.user = this.authService.getUser();
    this.authenticated = this.authService.isAuthenticated();
  }

  logout() {
    this.authService.logout().subscribe(json => {
    this.router.navigate(['/user/login']);
    });
  }

}
