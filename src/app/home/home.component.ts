import { Component, OnInit, AfterViewInit, ElementRef, Inject } from '@angular/core';
import { SessionService } from '../service/session.service'
import { PageScrollConfig, PageScrollService, PageScrollInstance } from 'ng2-page-scroll';
import { DOCUMENT} from '@angular/common';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements AfterViewInit {
  listCategories = [];
  metadata = {
    menu:''
  }
  private container: ElementRef;

  constructor(protected sessionService: SessionService, private pageScrollService: PageScrollService, @Inject(DOCUMENT) private document: any, private router: Router, private activatedRoute: ActivatedRoute) { }

  ngAfterViewInit() {
    this.activatedRoute.params.subscribe((params: Params) => {
      console.log(params['id']);
      if(params['id'] != 'index'){
        this.metadata.menu = params['id'];
        this.goToHead(this.metadata.menu);
      }
    });
    this.list();
  }
  goToHead(head){
    this.metadata.menu = head;
    let pageScrollInstance: PageScrollInstance = PageScrollInstance.simpleInstance(this.document, '#'+head);
    this.pageScrollService.start(pageScrollInstance);
  }
  /*
   funcion para listar el catalogo de categorias
  */
  list(){
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
