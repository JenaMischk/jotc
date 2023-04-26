import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, tap, throwError } from 'rxjs';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { DataService } from './data.service';


export interface User {
  email: string;
  firstName: string;
  lastName: string;
  birthDate: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    private data: DataService,
    private toaster: ToastrService,
    private router: Router
  ) {

  }

  login(user: User) {

    return this.data.post('http://localhost:4000/login', user).pipe(
      tap((res) => {
        this.setSession(res);
        this.toaster.success('User login successful', 'Success');
        this.router.navigate(['jotc']);
      })
    );

  }
        
  private setSession(authResult: any) {
      localStorage.setItem('jotc_token', authResult.token);
  }          

  logout() {
    localStorage.removeItem("jotc_token");
    this.router.navigate(['']);
  }

  public isLoggedIn() {
      return localStorage.getItem('jotc_token') ? true : false;
  }

  public isLoggedOut() {
    return !this.isLoggedIn();
  }

}
