import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { CategoriesUpdateComponent } from './categories-update.component';
import { SessionService } from '../../service/session.service'


@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css']
})
export class CategoriesComponent implements OnInit {
  private _opened: boolean = true;
  listCategories = [];
  categoriesMin = {
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
    this.sessionService.postRequest('category:list',this.categoriesMin).subscribe((data:any)=>{
      this.listCategories = data.object.list;
      for( let i=0; i<this.listCategories.length; i++){
        this.listCategories[i].file = 'http://www.grana.mx/gallery/'+this.listCategories[i].file;
      }
    },
    (error)=>{
      console.log('Error:category:list',error)
    })
  }

  /*
  * Modal para crear categorias
  */
  viewUpdateCategories(object) {
    const  modalRef = this.modalService.open(CategoriesUpdateComponent);
    modalRef.componentInstance.data = object;
    modalRef.result.then((result) => {
      if(result){
        this.listCategories = [];
        this.list();
      }
    }, (reason) => {
      console.log(reason);
    });
  }

}
