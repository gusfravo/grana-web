import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { SessionService } from '../../service/session.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {
  @Output() change: EventEmitter<boolean> = new EventEmitter<boolean>();
  toogle:boolean = true;
  constructor(protected sessionService: SessionService, private router: Router) { }

  ngOnInit() {
  }

  toogleMenu(){
    if(this.toogle){
      this.toogle = false;
    }else{
      this.toogle = true;
    }
    this.change.emit(this.toogle);
  }
   logout(){
     this.sessionService.logout().then(()=>{
       this.router.navigate(['/login']);
     })
   }

}
