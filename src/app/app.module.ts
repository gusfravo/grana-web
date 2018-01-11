import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { SidebarModule } from 'ng-sidebar';
import { FileUploadModule } from 'ng2-file-upload';

import { AppComponent } from './app.component';
import { SessionService } from './service/session.service';
import { AdministrationComponent } from './administration/administration.component';
import { CategoriesComponent } from './administration/categories/categories.component';
import { MenuComponent } from './administration/menu/menu.component';
import { CategoriesUpdateComponent } from './administration/categories/categories-update.component';

@NgModule({
  declarations: [
    AppComponent,
    AdministrationComponent,
    CategoriesComponent,
    MenuComponent,
    CategoriesUpdateComponent
  ],
  imports: [
    BrowserModule,
    HttpModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    FileUploadModule,
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
  bootstrap: [AppComponent],
  entryComponents: [
    CategoriesUpdateComponent,
  ]
})
export class AppModule { }
