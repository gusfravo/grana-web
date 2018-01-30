import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { SidebarModule } from 'ng-sidebar';
import { FileUploadModule } from 'ng2-file-upload';
import { ScrollSpyModule } from 'ngx-scrollspy';
import { Ng2PageScrollModule } from 'ng2-page-scroll';
import { ParallaxModule } from 'ngx-parallax';
import { ImageViewerModule } from "ngx-image-viewer";

import { AppComponent } from './app.component';
import { SessionService } from './service/session.service';
import { AdministrationComponent } from './administration/administration.component';
import { CategoriesComponent } from './administration/categories/categories.component';
import { MenuComponent } from './administration/menu/menu.component';
import { CategoriesUpdateComponent } from './administration/categories/categories-update.component';
import { ProductComponent } from './administration/product/product.component';
import { ProductUpdateComponent } from './administration/product/product-update.component';
import { LoginComponent } from './login/login.component';
import { AuthenticationGuard } from './service/authentication.guard';
import { HomeComponent } from './home/home.component';
import { CategoriesProductsComponent } from './home/categories-products/categories-products.component';

@NgModule({
  declarations: [
    AppComponent,
    AdministrationComponent,
    CategoriesComponent,
    MenuComponent,
    CategoriesUpdateComponent,
    ProductComponent,
    ProductUpdateComponent,
    LoginComponent,
    HomeComponent,
    CategoriesProductsComponent,
  ],
  imports: [
    BrowserModule,
    HttpModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    FileUploadModule,
    SidebarModule.forRoot(),
    ScrollSpyModule.forRoot(),
    Ng2PageScrollModule,
    ParallaxModule,
    NgbModule.forRoot(),
    ImageViewerModule.forRoot(),
    RouterModule.forRoot([
      { path: '', redirectTo: 'home/index', pathMatch: 'full'},
      { path: 'home/:id',component:HomeComponent },
      { path: 'login',component:LoginComponent },
      { path: 'home/product/:categoryName/:categoryUuid',component:CategoriesProductsComponent },
      { path: 'administration', canActivate:[AuthenticationGuard], component: AdministrationComponent },
      { path: 'administration/categories', canActivate:[AuthenticationGuard], component: CategoriesComponent },
      { path: 'administration/product', canActivate:[AuthenticationGuard], component: ProductComponent },
      { path: 'administration/product/update/:id', canActivate:[AuthenticationGuard], component: ProductUpdateComponent },
    ],{ useHash: true }),
  ],
  providers: [
    SessionService,
    AuthenticationGuard,
  ],
  bootstrap: [AppComponent],
  entryComponents: [
    CategoriesUpdateComponent,
    ProductUpdateComponent,
  ]
})
export class AppModule { }
