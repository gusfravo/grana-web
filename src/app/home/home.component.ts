import { Component, OnInit, AfterViewInit } from '@angular/core';
import { SessionService } from '../service/session.service'
import { ScrollSpyModule, ScrollSpyService } from 'ngx-scrollspy';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements AfterViewInit {
  listCategories = [];

  constructor(protected sessionService: SessionService, private scrollSpyService: ScrollSpyService) { }

  ngAfterViewInit() {
    this.list();
    // this.scrollSpyService.getObservable('window').subscribe((e: any) => {
		// 	console.log('ScrollSpy::window: ', e);
		// });
  }
  /*
   funcion para listar el catalogo de categorias
  */
  list(){
    this.sessionService.postRequest('category:list',{}).subscribe((data:any)=>{
      this.listCategories = data.object.list;
      for( let i=0; i<this.listCategories.length; i++){
        this.listCategories[i].file = 'http://www.grana.mx/gallery/'+this.listCategories[i].file;
      }
    },
    (error)=>{
      console.log('Error:category:list',error)
    })
  }

}
