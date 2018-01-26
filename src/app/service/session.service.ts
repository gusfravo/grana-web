import { Injectable } from '@angular/core';
import { Http, Headers, RequestMethod, RequestOptions, RequestOptionsArgs, Response } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import 'rxjs/add/observable/throw';
import { SERVER_URL } from './server-url';
import { Urls } from './api/request-url';


// Urls de petición al backend
let urls = Urls;

// Hostname del backend
let server_url = SERVER_URL;

@Injectable()
export class SessionService {
  private isUserLoggedIn;

  constructor(protected http: Http) {
    this.isUserLoggedIn = false;
  }

  getLoggedIn() {
    return this.isUserLoggedIn;
  }

  login(object) {
    return new Promise((resolve)=>{
      this.postRequest("user:login",object).subscribe((data:any)=>{
        this.isUserLoggedIn = true;
        resolve(data);
      },
      error =>{
        resolve(false);
      })
    })
  }
  logout(){
    return new Promise((resolve)=>{
      this.isUserLoggedIn = false;
      resolve(true);
    })
  }


  /*
  * funcion para ejecutar petioones anonimas
  */
  // funcion para realizar peticiones POST
  postRequest(code:string,object:any):Observable<Response> {
    //buscamos el code en el compendio de urls
    let codeUrl = urls.find(x => x.code == code);
    //variable que contendra el path a llamar por el http metodo get
    let path = "";
    if(codeUrl != undefined){
      return Observable.create(observer=>{
        path = server_url + codeUrl.url;
        let headers = new Headers({'Content-Type': 'application/json'});
        // headers.append("Authorization", "Basic " + btoa('oasys@oasys.mx' + ":" + 'admin'));
        let options: RequestOptionsArgs = new RequestOptions({
            method: RequestMethod.Post,
            headers: headers,
        });
        // mandamos a llamar el servicio http para hacer peticion GET.
        this.http.post(path , object , options)
        .subscribe((response:Response)=>{
          let res = response.json();
          if(res.transaction == 'ok'){
            console.log("Request :: ", path);
            observer.next(res);
            observer.complete();
          }else{
            observer.error(res);
          }
        },
        error =>{
          observer.error(error);
        });
      });
    }else{
      throw new Error('La URL no existe:' + code);
    }
  }

  /**
   * Función para obtener la url de consulta del backend
   */
  getUrl(code: string) {
    //buscamos el code en el compendio de urls
    let url = urls.find(x => x.code == code);

    //variable que contendra el path a llamar por el http metodo get
    if (url === undefined) {
      console.log('Error: La url que desea accesar no esta definida');
    } else {
      return url.url;
    }
  }

}
