import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SessionService } from '../../service/session.service'
import { Router, ActivatedRoute, Params } from '@angular/router';
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-categories-products',
  templateUrl: './categories-products.component.html',
  encapsulation: ViewEncapsulation.None,
  styleUrls: ['./categories-products.component.css']
})
export class CategoriesProductsComponent implements OnInit {
  listCategories = [];
  productsList = [];
  data = '';
  categoriesModel = {
    id:'',
    name:'',
    file:'',
    type:'',
    base64:'',
    description:''
  }
  productModel = {
    id:'',
    name:'',
    file:'',
    type:'',
    base64:'',
    description:'',
    color:'',
    measurements:'',
    price:'',
    region:'',
    technique:'',
    town:'',
    images:[]
  }

  constructor(protected sessionService: SessionService, private router: Router, private activatedRoute: ActivatedRoute, private modalService: NgbModal) { }

  ngOnInit() {

    this.getCategories().then(data=>{
      this.activatedRoute.params.subscribe((params: Params) => {
        this.data = params['categoryUuid'];
        if(this.data == 'all'){
          this.categoriesModel.id = this.listCategories[0].id;
          this.colorCategory(this.categoriesModel);
        }else{
          this.categoriesModel.id = this.data;
          this.colorCategory(this.categoriesModel);
        }
      });
    });
  }
  /*
   funcion para listar el catalogo de categorias
  */
  getCategories(){
    return new Promise(resolve=>{
      this.sessionService.postRequest('category:list',{}).subscribe((data:any)=>{
        this.listCategories = data.object.list;
        for( let i=0; i<this.listCategories.length; i++){
          this.listCategories[i].file = 'http://www.grana.mx/gallery/'+this.listCategories[i].file;
        }
        resolve(this.listCategories);
      },
      (error)=>{
        console.log('Error:category:list',error)
        resolve(false);
      })
    });
  }
  /*
  *Funcion colorear un categoria.
  */
  colorCategory(object){
    this.sessionService.postRequest('category:get',object).subscribe((data:any)=>{
     this.categoriesModel = data.object;
     // Coloreamos el ID de la categoria seleccinada
     for(let item of this.listCategories){
       item.selected = false;
        if(item.id == object.id){
          item.selected = true;
        }
      }
      this.findAllByCategory();
    },
    (error)=>{
      console.log('Error:category:get',error)
    })
  }

  /*
  funcion para obetner los productos de un categoria.
  */
  findAllByCategory(){
    this.productsList = [];
    this.sessionService.postRequest('categoryProduct:findAllByCategory',{category:{id:this.categoriesModel.id}}).subscribe((data:any)=>{
      this.productsList = data.object.list;
      for( let i=0; i<this.productsList.length; i++){
        this.productsList[i].product.images = [];
        this.getImagesFromProduct(this.productsList[i]);
      }
      // console.log(this.productsList);
    },
    (error)=>{
      console.log('Error:category:list',error)
    })
  }

  /*
  * funcion para obetner la images de un prodcuto
  */
  getImagesFromProduct(object){
    this.sessionService.postRequest('productImages:findAllByProduct',{product:{id:object.product.id}}).subscribe((data:any)=>{
      object.product.images = data.object.list;
      // console.log(object);
      for( let i=0; i<object.product.images.length; i++){
        object.product.images[i].image = 'http://www.grana.mx/gallery/'+object.product.images[i].image;
      }
      // buscamos si el producto tiene asociados categorias.
    },
    (error)=>{
      console.log('Error:productImages:findAllByProduct',error)
    })
  }
  /* holas */
  open(content,object) {
    this.productModel = object.product;
    console.log(this.productModel);
    if(this.productModel.images.length > 0){
      this.productModel.file = this.productModel.images[0].image;
    }
    this.modalService.open(content,{ windowClass: 'modal-content-products', size:'lg'}).result.then((result) => {
      console.log(result)
    }, (reason) => {
      console.log(reason);
    });
  }
  /* funcion para cambiar de imagen*/
  changeImage(image){
    this.productModel.file = image;
  }

}
