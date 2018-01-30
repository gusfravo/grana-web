import { Component, OnInit, ViewEncapsulation, ViewChild } from '@angular/core';
import { FormControl, Validators, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from '../service/session.service';
import { Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { NotificationsService } from 'angular2-notifications';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  object = {
    main: {
      username:'',
      password:''
    }
  }

  isLoggin = true;
  metadata = {
    company:{
      id:''
    }
  }
  companyList = [];
  complexForm: FormGroup;

  constructor(public fb: FormBuilder, private session: SessionService, private router: Router, private modalService: NgbModal, public notificationCtrl: NotificationsService) {
    this.complexForm = fb.group({
      // To add a validator, we must first convert the string value into an array. The first item in the array is the default value if any, then the next item in the array is the validator. Here we are adding a required validator meaning that the firstName attribute must have a value in it.
      'username': [null, Validators.required],
      'password': [null, Validators.compose([Validators.required])],
    });
  }

  ngOnInit() {
  }
  // metod para realizar Login
  login(object) {
    // this.loadingService.show(true,'Iniciando sesion');
    // Mandamos a invocar el servicio de login
    this.session.login(object).then((data:any)=> {
      if (data.transaction === 'ok') {
        this.notificationCtrl.success('Inicio de Sesión Correctamente')
        this.router.navigate(['administration']);

      } else {
        this.notificationCtrl.error('Usuario o Contraseña incorrectos')
      }
    }).catch((error)=>{
      this.notificationCtrl.error('Usuario o Contraseña incorrectos')
      console.log('Error: ', error);
    });
  }

}
