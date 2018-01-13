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
  productMin = {
    max:0,
    offset:0
  }

  constructor(private modalService: NgbModal, protected sessionService: SessionService) { }

  ngOnInit() {
    this.list();
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
}
