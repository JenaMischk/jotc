import { Component, Injectable } from '@angular/core';
import { AbstractControl, FormBuilder, ValidatorFn, Validators } from '@angular/forms';
import * as moment from 'moment';
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
      Validators.required, this.dateValidator()])
    ]
  });

  constructor(
    private fb: FormBuilder,
    public auth: AuthService
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

  dateValidator(): ValidatorFn {
    return (control: AbstractControl): {[key: string]: any} | null => {

      const date = moment(control.value);
      const eighteenYearsAgo = moment().subtract(18, 'year');
  
      if(!(control && control.value)) {
        // if there's no control or no value, that's ok
        return null;
      }
  
      console.log(date.format('DD/MM/YYYY'));
      console.log(eighteenYearsAgo.format('DD/MM/YYYY'));
      // return null if there's no errors
      return eighteenYearsAgo.isBefore(date)
        ? {underEighteen: true } 
        : null;
        
    }
  }

}
