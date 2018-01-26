import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ProductUpdateComponent } from './product-update.component';
import { SessionService } from '../../service/session.service';

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
    offset:0
  }
  metadata = {
    category:{
      id: ''
    }
  }

  constructor(private modalService: NgbModal, protected sessionService: SessionService) { }

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
}
