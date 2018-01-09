import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css']
})
export class CategoriesComponent implements OnInit {
  private _opened: boolean = true;
  constructor() { }

  ngOnInit() {
  }
  private _toggleSidebar() {
    this._opened = !this._opened;
  }
  private toogleMenu(ev){
    this._opened = ev;
  }

}
