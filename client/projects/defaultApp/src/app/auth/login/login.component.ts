import {Component, OnInit} from '@angular/core';
import {AuthService} from '../../shared/auth/auth.service';
import {ApiService} from '../../shared/api/api.service';
import {LoginFormModel} from './login-form.model';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  loading = false;
  message = '';
  errors: any = {};
  model: LoginFormModel = new LoginFormModel();
  loginForm: FormGroup;
  constructor(
    private apiService: ApiService,
    private authService: AuthService,
    private router: Router) {
    this.loginForm = new FormGroup({
      email: new FormControl('', [Validators.required, Validators.email, Validators.minLength(6)]),
      password: new FormControl('', [Validators.required, Validators.minLength(6)])
    });
  }
  get email() {
    return this.loginForm.get('email');
  }

  get password() {
    return this.loginForm.get('password');
  }

  submitLoginForm() {
    this.loading = true;
    this.apiService.post('/login', this.loginForm.value, 'auth').subscribe(
      (data) => {
        this.authService.refreshUser().subscribe(() => {
          this.router.navigate(['admin/dashboard']);
        });
        this.loading = false;
      },
      (error: any) => {
        this.message = '';
        if (error.statusCode !== 422) {
          this.message = error.message;
        }
        this.errors = error.errors;
        this.loading = false;

      }
    );
  }
}
