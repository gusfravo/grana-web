import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-administration',
  templateUrl: './administration.component.html',
  styleUrls: ['./administration.component.css']
})
export class AdministrationComponent implements OnInit {
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
