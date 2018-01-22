import { Component, OnInit } from '@angular/core';
import { SessionService } from '../../service/session.service'

@Component({
  selector: 'app-categories-products',
  templateUrl: './categories-products.component.html',
  styleUrls: ['./categories-products.component.css']
})
export class CategoriesProductsComponent implements OnInit {
  listCategories = [];

  constructor(protected sessionService: SessionService) { }

  ngOnInit() {
    this.getCategories();
  }
  /*
   funcion para listar el catalogo de categorias
  */
  getCategories(){
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
