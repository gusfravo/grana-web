import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { SidebarModule } from 'ng-sidebar';

import { AppComponent } from './app.component';
import { SessionService } from './service/session.service';
import { AdministrationComponent } from './administration/administration.component';
import { CategoriesComponent } from './administration/categories/categories.component';
import { MenuComponent } from './administration/menu/menu.component';

@NgModule({
  declarations: [
    AppComponent,
    AdministrationComponent,
    CategoriesComponent,
    MenuComponent
  ],
  imports: [
    BrowserModule,
    HttpModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    SidebarModule.forRoot(),
    NgbModule.forRoot(),
    RouterModule.forRoot([
      { path: '', redirectTo: 'administration', pathMatch: 'full'},
      { path: 'administration', component: AdministrationComponent },
      { path: 'administration/categories', component: CategoriesComponent }
    ],{ useHash: true }),
  ],
  providers: [
    SessionService,
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
