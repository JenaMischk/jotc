import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError, tap, throwError } from 'rxjs';
import { AuthService } from '../services/auth.service';
import { ToastrService } from 'ngx-toastr';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(
    private auth: AuthService,
    private toaster: ToastrService
  ) {}

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    
    const token = localStorage.getItem('jotc_token');
    let res;

    if (token) {
        const cloned = request.clone({
            headers: request.headers.set("Authorization",
                "Bearer " + token)
        });
        res = next.handle(cloned);
    }
    else {
        res = next.handle(request)
    }

    return res.pipe(catchError(err => {
      if ([401, 403].includes(err.status) && this.auth.isLoggedIn()) {
        this.toaster.error('', 'Authentication failure');
        this.auth.logout();
      }
      const error = err.error?.message || err.statusText;
      console.error(err);
      return throwError(() => error);
    }));

  }
}
