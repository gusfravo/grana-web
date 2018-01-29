import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ProductUpdateComponent } from './product-update.component';
import { SessionService } from '../../service/session.service';
import * as Rx from 'rxjs/Rx';

@Component({
  selector: 'app-product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent implements OnInit {
  private _opened: boolean = true;
  listProduct = [];
  listCategories = [];
  productMin = {
    max:0,
    offset:0,
    name:''
  }
  metadata = {
    category:{
      id: ''
    },
    searchBoxInput:  new Rx.Subject<string>()
  }

  constructor(private modalService: NgbModal, protected sessionService: SessionService) {
    this.metadata.searchBoxInput.debounceTime(700)
    .switchMap(val => {
      // console.log('called once',val)
      if(val != ''){
        this.productMin.name = val;
        this.findAllByName();
      }else{
        this.list();
      }
      return val;
    }).subscribe(results => {
      // Modificaciones sobre cada letra si se requiere
    }, error => {
    // console.log('error logged:');
    // console.log(error);
  });
   }

  ngOnInit() {
    this.list();
    this.getCategories();
  }
  private _toggleSidebar() {
    this._opened = !this._opened;
  }
  private toogleMenu(ev){
    this._opened = ev;
  }

  /*
   funcion para listar el catalogo de categorias
  */
  list(){
    this.sessionService.postRequest('product:list',this.productMin).subscribe((data:any)=>{
      this.listProduct = data.object.list;
    },
    (error)=>{
      console.log('Error:product:list',error)
    })
  }

  /*
   funcion para buscar productos por nombre
  */
  findAllByName(){
    this.sessionService.postRequest('product:findLikeName',{name: '%'+this.productMin.name+'%'}).subscribe((data:any)=>{
      this.listProduct = data.object.list;
    },
    (error)=>{
      console.log('Error:product:findLikeName',error)
    })
  }

  searchProduct(){
    if(this.productMin.name != ''){
      this.metadata.searchBoxInput.next(this.productMin.name);
    }else{
      this.list();
    }
  }


  /*
  * Modal para crear categorias
  */
  viewUpdateProduct(object) {
    const  modalRef = this.modalService.open(ProductUpdateComponent);
    modalRef.componentInstance.data = object;
    modalRef.result.then((result) => {
      if(result){
        this.listProduct = [];
        this.list();
      }
    }, (reason) => {
      console.log(reason);
    });
  }
  getCategories(){
    return new Promise(resolve=>{
      this.sessionService.postRequest('category:list',{}).subscribe((data:any)=>{
        this.listCategories = data.object.list;
        resolve(this.listCategories);
      },
      (error)=>{
        console.log('Error:category:list',error)
        resolve(false);
      })
    });
  }
  /*
  * funcion para obtener una lista de productos por categoria.
  */
  findAllByCategory(){
    if(this.metadata.category.id != ''){
      this.sessionService.postRequest('categoryProduct:findAllByCategory',{category:{id:this.metadata.category.id}}).subscribe((data:any)=>{
        this.listProduct = [];
        for( let i=0; i<data.object.list.length; i++){
          this.listProduct.push(data.object.list[i].product);
        }
      },
      (error)=>{
        console.log('Error:category:list',error)
      })
    }else{
      this.list();
    }

  }
  /*
  Funcion para eliminar un producto.
  */
  deleteAProduct(object){
    this.sessionService.postRequest('product:delete',object).subscribe((data:any)=>{
      for(let i=0; i<this.listProduct.length; i++){
        if(this.listProduct[i].id == object.id){
          this.listProduct.splice(i,1);
        }
      }
    },
    (error)=>{
      console.log('Error:product:delete',error)
    })
  }
}
