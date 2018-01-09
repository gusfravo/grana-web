import { Component, OnInit, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {
  @Output() change: EventEmitter<boolean> = new EventEmitter<boolean>();
  toogle:boolean = true;
  constructor() { }

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

}
