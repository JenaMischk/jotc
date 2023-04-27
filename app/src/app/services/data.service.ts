import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { catchError, tap, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  constructor(
    private http: HttpClient,
    private toaster: ToastrService
  ) { }

  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type':  'application/json'
    }),
    //withCredentials: true
  };

  loading = false;


  get(url: string, queryParams = ''){
    this.loading = true;
    return this.http.get(url, this.httpOptions).pipe(
      tap((res) => {
        this.loading = false;
      }),  
      catchError(err => this.handleError(err)));
  }

  post(url: string, body: object){
    this.loading = true;
    return this.http.post(url, body, this.httpOptions).pipe(
      tap((res) => {
        this.loading = false;
      }),  
      catchError(err => this.handleError(err)));
  }

  handleError(error: HttpErrorResponse) {
    this.loading = false;
    if (error.status === 0) {
      // A client-side or network error occurred. Handle it accordingly.
      console.error('An error occurred:', error.error);
    } else {
      // The backend returned an unsuccessful response code.
      // The response body may contain clues as to what went wrong.
      console.error(`Backend returned code ${error.status}, body was: `, error.error);
      this.toaster.error(JSON.stringify(error), 'API Error');
    }
    // Return an observable with a user-facing error message.
    return throwError(() => new Error('Something is not working. Please try again later.'));
  }

  public getLoadingStatus(){
    return this.loading;
  }

}
