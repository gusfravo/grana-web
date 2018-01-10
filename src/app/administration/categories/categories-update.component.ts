import { Component, OnInit, Input } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { FormControl, Validators, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from '../../service/session.service'

@Component({
  selector: 'app-categories-update',
  templateUrl: './categories-update.component.html',
  styleUrls: ['./categories-update.component.css']
})
export class CategoriesUpdateComponent implements OnInit {
  complexForm: FormGroup;
  categoriesModel = {
    id:'',
    name:'',
    file:'',
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
      // cargamos los datos de la BD
    }
  }

  send(object){
    console.log(object);
    this.sessionService.postRequest('category:update',object).subscribe(data=>{
      this.activeModal.close(true);
    },
    (error)=>{
      console.log('Error:category:update',error)
    })
  }

  /*
  funcion para cerrar un modal
  */
  closeModal(){
    this.activeModal.close(false);
  }

}
