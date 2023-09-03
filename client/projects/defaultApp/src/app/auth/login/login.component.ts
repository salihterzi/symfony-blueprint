import { Component, } from '@angular/core';
import {AuthService} from '../../shared/auth/auth.service';
import {ApiService} from '../../shared/api/api.service';
import {LoginFormModel} from './login-form.model';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {validatePassword} from "@ngx-validate/core";
const { minLength, required, requiredTrue ,email } = Validators;
const validPassword = validatePassword(['small', 'capital', 'number']);

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
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
    private fb: FormBuilder,
    private router: Router) {

    const credentials = this.fb.group(
      {
        email: [null, [required, minLength(6),email]],
        password: [null, [required, minLength(6), validPassword]],
      }
    );

    this.loginForm = this.fb.group({ credentials });
  }

  submitLoginForm() {
    this.loading = true;
    this.apiService.post('/login', this.loginForm.value.credentials, 'auth').subscribe(
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
