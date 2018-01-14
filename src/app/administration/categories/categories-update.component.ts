import { Component, OnInit, Input } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { FormControl, Validators, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from '../../service/session.service'
import { FileUploader } from 'ng2-file-upload';

@Component({
  selector: 'app-categories-update',
  templateUrl: './categories-update.component.html',
  styleUrls: ['./categories-update.component.css']
})
export class CategoriesUpdateComponent implements OnInit {
  complexForm: FormGroup;
  uploader:FileUploader = new FileUploader({url: ''});
  categoriesModel = {
    id:'',
    name:'',
    file:'',
    type:'',
    base64:'',
    description:''
  }
  object = JSON.parse(JSON.stringify(this.categoriesModel));
  @Input() data;


  constructor(public activeModal: NgbActiveModal, public fb: FormBuilder, protected sessionService: SessionService) {
    this.complexForm = fb.group({
      // To add a validator, we must first convert the string value into an array. The first item in the array is the default value if any, then the next item in the array is the validator. Here we are adding a required validator meaning that the firstName attribute must have a value in it.
      'name': [null, Validators.compose([Validators.required])],
    });
   }

  ngOnInit() {
    if(this.data == 'new'){
      // no hacemos nada por el momento
    }else{
    this.object.id = this.data;
      // cargamos los datos de la BD
      this.sessionService.postRequest('category:get',this.object).subscribe((data:any)=>{
       this.object = data.object;
      },
      (error)=>{
        console.log('Error:category:update',error)
      })
    }
  }

  send(object){
    console.log(object);
    this.fileUpload(this.uploader).then((data)=>{
      if(data){
        this.object.base64 = data;
        object.file = object.file;
        this.sessionService.postRequest('category:update',object).subscribe(data=>{
          this.sessionService.postRequest('ftp:loadFile',object).subscribe(data=>{
            this.activeModal.close(true);
          },
          (error)=>{
            console.log('Error:ftp:loadFile',error)
          })
        },
        (error)=>{
          console.log('Error:category:update',error)
        })
      }else{
        this.sessionService.postRequest('category:update',object).subscribe(data=>{
          this.activeModal.close(true);
        },
        (error)=>{
          console.log('Error:category:update',error)
        })
      }

    });

  }

  /*
  * funcion para convertir un archivo en base 64
  */
  fileUpload(fileItem){
    return new Promise(resolve => {
      if(fileItem.queue.length > 0){
        console.log(fileItem);
        this.object.file= fileItem.queue[0].file.name;
        this.object.type= fileItem.queue[0].file.type;
        var reader = new FileReader();
        reader.onload = (event:any) => {
          resolve(event.target.result);
        };
        reader.readAsDataURL(fileItem.queue[0]._file);
      }else{
        console.log("no hay archivo")
        resolve(false);
      }
    });

  }
  /*
  *Eliminar una imagen
  */
  deleteAImage(){
    this.object.file = '';
  }

  /*
  funcion para cerrar un modal
  */
  closeModal(){
    this.activeModal.close(false);
  }

}
