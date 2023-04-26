import { Component } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { tap } from 'rxjs';
import { DataService } from 'src/app/services/data.service';

@Component({
  selector: 'app-jotc',
  templateUrl: './jotc.component.html',
  styleUrls: ['./jotc.component.css']
})
export class JotcComponent {

  constructor(
    private data: DataService,
    private toaster: ToastrService
  ){

  }

  clouds = [0, 0, 0];
  jotcSolution: any;

  addCloud(){
    this.clouds.push(0);
  }

  toggleCloud(index: number){
    if(index != 0 && index != this.clouds.length-1){
      this.clouds[index] = 1 -this.clouds[index];
      return;
    }
    this.toaster.warning('The first and last clouds must be accessible', 'Can not set cloud as avoidable');
  }

  removeCloud(index: number){
    if(this.clouds.length === 2){
      this.toaster.warning('The first and last clouds must always exist', 'Can not remove cloud');
      return;
    }
    this.clouds.splice(index, 1);
    if(this.clouds[0] === 1){
      this.clouds[0] = 0;
      this.toaster.warning('We have automatically changed the first cloud\'s state', 'First cloud must be accessible');
    }
    if(this.clouds[this.clouds.length-1] === 1){
      this.clouds[this.clouds.length-1] = 0;
      this.toaster.warning('We have automatically changed the last cloud\'s state', 'Last cloud must be accessible');
    }
  }

  submit(){
    this.data.post('http://localhost:4000/jotc', {clouds: this.clouds}).pipe(
      tap<any>((res) => {
        if(res.total === 0){
          this.toaster.warning('This particular input can not be solved (maybe you have two forbidden clouds in a row?)', 'Impossible to solve');
        }
      }))
      .subscribe( (res) => {
        this.jotcSolution = res;  
    });
  }

}
