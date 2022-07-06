import {Injectable} from '@angular/core';
import {Observable, Subject, throwError, TimeoutError} from 'rxjs';
import {catchError, map, timeout} from 'rxjs/operators';
import {HttpClient, HttpErrorResponse, HttpHeaders, HttpParams, HttpXsrfTokenExtractor} from '@angular/common/http';
import {ApiModel} from './api.model';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private readonly headers: HttpHeaders;
  baseUrl: '/ajax/';
  timeout: number;
  private successSource: Subject<any>;
  private infoSource: Subject<any>;
  private errorSource: Subject<any>;

  get success$() {
    return this.successSource.asObservable();
  }

  get error$() {
    return this.errorSource.asObservable();
  }

  constructor(private http: HttpClient,
              private tokenExtractor: HttpXsrfTokenExtractor) {
    this.successSource = new Subject<any>();
    this.infoSource = new Subject<any>();
    this.errorSource = new Subject<any>();
    this.timeout = 30000;
    this.headers = new HttpHeaders({
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
  }

  private handleResult(json: ApiModel) {
    this.successSource.next(json);
    return json;
  }

  private handleError(err: HttpErrorResponse) {
    const statusCode: number = err.status;
    const message: string = err.error.message ? err.error.message : err.statusText;
    const errors: any = err.error.errors ? err.error.errors : [];

    const error: any = {statusCode: statusCode, message: message, errors: errors};

    this.errorSource.next(error);
    return throwError(error);
  }

  get(path: string, params: any = {}, baseUrl: string = this.baseUrl,
      requestTimeout: number = this.timeout): Observable<ApiModel> {
    let headers = this.headers;

    const token = this.tokenExtractor.getToken() as string;
    if (token !== null) {
      headers = headers.append('X-XSRF-TOKEN', token);
    }

    let urlParams: HttpParams = new HttpParams();
    for (const param in params) {
      if (params.hasOwnProperty(param)) {
        urlParams = urlParams.append(param, params[param]);
      }
    }

    return this.http.get<ApiModel>(`${baseUrl}${path}`, {headers, params: urlParams, withCredentials: true})
      .pipe(
        timeout(requestTimeout),
        catchError(err => this.handleError(err))
      );
  }

  post(path: string, params: any = {}, baseUrl: string = this.baseUrl,
       requestTimeout: number = this.timeout): Observable<ApiModel> {
    let headers = this.headers;
    const paramKeys = Object.keys(params);
    const multiform = paramKeys.some(k => this.isFileList(params[k]) || params[k] instanceof File);
    let body;
    if (multiform) {
      body = this.createFormData(params);
      headers = headers.delete('Content-Type');
    } else {
      body = JSON.stringify(params);
    }
    return this.http.post<ApiModel>(`${baseUrl}${path}`, body, {headers, withCredentials: true})
      .pipe(
        timeout(requestTimeout),
        map(json => this.handleResult(json)),
        catchError(err => this.handleError(err))
      );
  }

  private createFormData(params: any): FormData {
    const formData: any = new FormData();
    for (const key of Object.keys(params)) {
      const param = params[key];
      if (this.isFileList(param)) {
        if (param.length > 0) {
          // tslint:disable-next-line:prefer-for-of
          for (let i = 0; i < param.length; i++) {
            formData.append(key, param[i], param[i].name);
          }
        }
      } else if (param instanceof File) {
        formData.append(key, param, param.name);
      } else {
        formData.append(key, JSON.stringify(param));
      }
    }
    return formData;
  }

  private isFileList(param: any): boolean {
    return param instanceof FileList || (Array.isArray(param) && param.every(p => p instanceof File));
  }
}
