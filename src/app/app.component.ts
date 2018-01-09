import { Component } from '@angular/core';
import { SessionService } from './service/session.service'
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'app';
  constructor(protected sessionService: SessionService){}
  testphp(){
    this.sessionService.postRequest('category:update',{
      id:'',
      name:'prueba1',
      description:'Esta es un descripcion'
    }).subscribe(data=>{
      console.log(data);
    })
  }
}
