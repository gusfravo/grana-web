import { Component, OnInit, Input } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { FormControl, Validators, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from '../../service/session.service'
import { FileUploader } from 'ng2-file-upload';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { NotificationsService } from 'angular2-notifications';

@Component({
  selector: 'app-product-update',
  templateUrl: './product-update.component.html',
  styleUrls: ['./product-update.component.css']
})
export class ProductUpdateComponent implements OnInit {
  private _opened: boolean = true;
  complexForm: FormGroup;
  uploader:FileUploader = new FileUploader({url: ''});
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
    town:''
  }
  categoryList = [];
  categoryProductList = [];
  metadata = {
    categoryProductModel:{
      id:'',
      category:{
        id:''
      },
      product:{
        id:''
      }
    },
    productImages:{
      id:'',
      image:'',
      product:{
        id:''
      }
    }
  }
  productImagesList = [];
  object = JSON.parse(JSON.stringify(this.productModel));
  data = '';
  constructor( public fb: FormBuilder, protected sessionService: SessionService, private router: Router, private activatedRoute: ActivatedRoute, public notificationCtrl: NotificationsService) {
    this.complexForm = fb.group({
      // To add a validator, we must first convert the string value into an array. The first item in the array is the default value if any, then the next item in the array is the validator. Here we are adding a required validator meaning that the firstName attribute must have a value in it.
      'name': [null, Validators.compose([Validators.required])],
      'price': [null, Validators.compose([Validators.required])],
    });
   }

   ngOnInit() {
     this.getCategories().then(data=>{
       this.activatedRoute.params.subscribe((params: Params) => {
         this.data = params['id'];
         if(this.data == 'new'){
           // no hacemos nada por el momento
         }else{
         this.object.id = this.data;
           // cargamos los datos de la BD
           this.sessionService.postRequest('product:get',this.object).subscribe((data:any)=>{
            this.object = data.object;
            this.getImagesFromProduct();
            // verificamos que categorias estan selecionadas.
            this.sessionService.postRequest('categoryProduct:findAllByProduct',{product:{id:this.object.id}}).subscribe((data:any)=>{
              this.categoryProductList = data.object.list;
              for(let item of this.categoryProductList){
                for(let i = 0; i < this.categoryList.length; i++){
                  if(this.categoryList[i].id == item.category.id){
                    this.categoryList.splice(i,1);
                  }
                }
              }

            },
            (error)=>{
              console.log('Error:categoryProduct:findAllByProduct',error)
            })
           },
           (error)=>{
             console.log('Error:product:update',error)
           })
         }
       });
     });
   }

   send(object,exit){
     return new Promise(resolve=>{
       this.sessionService.postRequest('product:update',object).subscribe((data:any)=>{
         if(exit){
           // salimos
           this.router.navigate(['/administration/product']);
           resolve(false)
         }else{
         this.object.id = data.object.id;
          resolve(true);
          }
       },
       (error)=>{
         console.log('Error:product:update',error)
       })
     })
   }

   /*
   * funcion para convertir un archivo en base 64
   */
   fileUpload(fileItem){
     return new Promise(resolve => {
       if(fileItem.queue.length > 0){
         console.log(fileItem);
         this.metadata.productImages.image= fileItem.queue[0].file.name;
         var reader = new FileReader();
         reader.onload = (event:any) => {
           resolve(event.target.result);
         };
         reader.readAsDataURL(fileItem.queue[0]._file);
       }
     });

   }
   /*
   funcion para subir una imagen
   */
   addImages(){
     this.metadata.productImages.product.id = this.object.id;
     this.fileUpload(this.uploader).then((data)=>{
       let base64Temp = data;
       this.notificationCtrl.info("Estamos subiendo la imagen, espera un momento.");
       this.sessionService.postRequest('productImages:update',this.metadata.productImages).subscribe((data:any)=>{
         this.metadata.productImages.id = data.object.id;
         this.sessionService.postRequest('ftp:loadFile',{base64:base64Temp,file:this.metadata.productImages.image}).subscribe(data=>{
           this.metadata.productImages.image = 'http://www.grana.mx/gallery/' + this.metadata.productImages.image;
           this.productImagesList.push(JSON.parse(JSON.stringify(this.metadata.productImages)))
           this.metadata.productImages.id = '';
           this.metadata.productImages.image = '';
           this.uploader = new FileUploader({url: ''});
           this.notificationCtrl.info("Imagen agregada con exito..");
         },
         (error)=>{
           console.log('Error:ftp:loadFile',error)
         })
       },
       (error)=>{
         console.log('Error:productImages:update',error)
       })
     });

   }
   /*
   * funcion para obetner la images de un prodcuto
   */
   getImagesFromProduct(){
     this.sessionService.postRequest('productImages:findAllByProduct',{product:{id:this.object.id}}).subscribe((data:any)=>{
       this.productImagesList = data.object.list;
       console.log(this.productImagesList);
       for( let i=0; i<this.productImagesList.length; i++){
         this.productImagesList[i].image = 'http://www.grana.mx/gallery/'+this.productImagesList[i].image;
       }
       // buscamos si el producto tiene asociados categorias.
     },
     (error)=>{
       console.log('Error:productImages:findAllByProduct',error)
     })
   }
   /*
   *obetenemos la lista de productos disponibles
   */
   getCategories(){
     return new Promise (resolve =>{
       this.sessionService.postRequest('category:list',{}).subscribe((data:any)=>{
         this.categoryList = data.object.list;
         resolve(this.categoryList);
         // buscamos si el producto tiene asociados categorias.
       },
       (error)=>{
         console.log('Error:category:list',error)
       })
     })
   }
   /*
   * funcion para seleccionar una categoria y producto
   */
   addCategory(objectId){
     this.send(this.object,false).then(data=>{
       let categoryProduct = JSON.parse(JSON.stringify(this.metadata.categoryProductModel));
       categoryProduct.product.id = this.object.id;
       // borramos la categoria de la lista de categorias.
       for(let i=0; i < this.categoryList.length; i++){
          if(objectId == this.categoryList[i].id){
            categoryProduct.category = JSON.parse(JSON.stringify(this.categoryList[i]));
            this.categoryList.splice(i,1);
            this.metadata.categoryProductModel.category.id = '';
            console.log(categoryProduct);
          }
        }
        // asiganmos la categoria a la lista de categorias y Productos
        this.sessionService.postRequest('categoryProduct:update',categoryProduct).subscribe((data:any)=>{
          categoryProduct.id = data.object.id;
          this.categoryProductList.push(categoryProduct);
        },
        (error)=>{
          console.log('Error:category:list',error)
        })
     })
   }
   /*
   * Funcion para eliminar un ralacion de categoria con producto
   */
   deleteACategory(object){
     this.sessionService.postRequest('categoryProduct:delete',object).subscribe((data:any)=>{
       for(let i=0; i<this.categoryProductList.length; i++){
         if(this.categoryProductList[i].id == object.id){
           this.categoryProductList.splice(i,1);
         }
       }
     },
     (error)=>{
       console.log('Error:category:list',error)
     })
   }
   /*
   * Funcion para eliminar un ralacion de producto con image
   */
   deleteAImage(object){
     this.sessionService.postRequest('productImages:delete',object).subscribe((data:any)=>{
       for(let i=0; i<this.productImagesList.length; i++){
         if(this.productImagesList[i].id == object.id){
           this.productImagesList.splice(i,1);
         }
       }
     },
     (error)=>{
       console.log('Error:productImages:list',error)
     })
   }
   private _toggleSidebar() {
     this._opened = !this._opened;
   }
   private toogleMenu(ev){
     this._opened = ev;
   }

}
