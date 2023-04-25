import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, throwError, tap } from 'rxjs';
import { ToastrService } from 'ngx-toastr';

@Injectable({
  providedIn: 'root'
})

export class UserService {

  loading = false;

  constructor(
    private http: HttpClient,
    private toaster: ToastrService
  ) { }

  public login(user: User){

    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type':  'application/json'
      }),
      withCredentials: true
    };

    this.loading = true;

    return this.http.post<User>('http://localhost:4000/login', user, httpOptions).pipe(
      tap(() => {
        this.loading = false;
        this.toaster.success('User login successful', 'Success');
      }),  
      catchError(err => this.handleError(err)));
    
  }

  public handleError(error: HttpErrorResponse) {
    this.loading = false;
    if (error.status === 0) {
      // A client-side or network error occurred. Handle it accordingly.
      console.error('An error occurred:', error.error);
    } else {
      // The backend returned an unsuccessful response code.
      // The response body may contain clues as to what went wrong.
      console.error(`Backend returned code ${error.status}, body was: `, error.error);
      this.toaster.error(JSON.stringify(error.error), 'User login failed');
    }
    // Return an observable with a user-facing error message.
    return throwError(() => new Error('Something is not working. Please try again later.'));
  }

  public getLoadingStatus(){
    return this.loading;
  }

}

export interface User {
  email: string;
  firstName: string;
  lastName: string;
  birthDate: string;
}
