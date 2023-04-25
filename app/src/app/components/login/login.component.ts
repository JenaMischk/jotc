import { Component, Injectable } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { AuthService, User } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})

@Injectable()
export class LoginComponent {

  loginForm = this.fb.group({
    email: [null, Validators.compose([
      Validators.required, Validators.email])
    ],
    firstName: [null, Validators.compose([
      Validators.required, Validators.minLength(3), Validators.maxLength(30)])
    ],
    lastName: [null, Validators.compose([
      Validators.required, Validators.minLength(3), Validators.maxLength(30)])
    ],
    birthDate: [null, Validators.compose([
      Validators.required, Validators.minLength(3), Validators.maxLength(30)])
    ]
  });

  constructor(
    private fb: FormBuilder,
    private auth: AuthService
  ) {}

  onSubmit(): void {

    let errors = [];
    Object.keys(this.loginForm.controls).forEach(key => {
      if(this.loginForm.get(key)!.errors){
        errors.push(this.loginForm.get(key)!.errors);
      }
    });

    if(!errors.length){
      let user: User = {
        email: this.loginForm.controls.email.value ?? '',
        firstName: this.loginForm.controls.firstName.value  ?? '',
        lastName: this.loginForm.controls.lastName.value  ?? '',
        birthDate: this.loginForm.controls.birthDate.value ?? ''
      };
      this.auth.login(user).subscribe( res => {

      });
    }

  }
}
