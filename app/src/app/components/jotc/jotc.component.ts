import { Component } from '@angular/core';

@Component({
  selector: 'app-jotc',
  templateUrl: './jotc.component.html',
  styleUrls: ['./jotc.component.css']
})
export class JotcComponent {

  constructor(){

  }

  clouds = [0, 0, 0];

  addCloud(){
    this.clouds.push(0);
  }

}
