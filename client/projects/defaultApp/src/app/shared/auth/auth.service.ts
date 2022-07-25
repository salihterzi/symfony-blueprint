import {Inject, Injectable} from '@angular/core';
import {ApiService} from '../api/api.service';
import {User} from './user.model';
import {Observable, of, Subject} from 'rxjs';
import {catchError, map} from 'rxjs/operators';
import {DOCUMENT} from '@angular/common';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private user: User | null;
  private authenticated = false;
  private sessionExpiredSource: Subject<void> = new Subject<void>();

  constructor(private apiService: ApiService, @Inject(DOCUMENT) private document: any) {
    this.user = null;
    this.apiService.error$.subscribe(err => {
      this.checkSessionExpiration(err);
    });
  }

  get sessionExpired$() {
    return this.sessionExpiredSource.asObservable();
  }

  init() {
    return new Promise((resolve, reject) => {
      this.refreshUser().subscribe({
        next: () => {
          resolve('Completed..');
        },
        error: () => {
          reject();
        }
      });
    });
  }

  refreshUser(): Observable<any> {
    return this.fetchUser()
      .pipe(
        map(user => {
          this.user = user;
          this.authenticated = !!user;
          return user;
        }),
        catchError(err => {
          this.user = null;
          this.authenticated = false;
          return of(null);
        }));
  }

  fetchUser(): Observable<User | null> {
    return this.apiService.get('auth/user', {}, '').pipe(map(json => {
      const data = json.data;

      if (data.user) {
        return {
          id: data.user.id,
          email: data.user.email,
          firstName: data.user.firstName,
          lastName: data.user.lastName,
        };
      } else {
        return null;
      }
    }));
  }

  getUser(): User | null {
    return this.user;
  }

  isAuthenticated(): boolean {
    return this.authenticated;
  }

  logout() {
    return this.apiService.post('/auth/logout', {}, '');
  }

  private checkSessionExpiration(err: any) {
    if (this.authenticated && err.statusCode && err.statusCode === 401) {
      this.sessionExpiredSource.next();
    }
  }
}
