import { Injectable }    from '@angular/core'
import { Headers, Http } from '@angular/http'

import 'rxjs/add/operator/toPromise'

import { NewsObject } from './news.object'
import { NewsImageObject } from './newsImage.object'

@Injectable()
export class NewsService {

  private headers = new Headers({'Content-Type': 'application/json'})
  private newsUrl = 'http://127.0.0.1:8000/api/v1/news'
  private newsImageUrl = 'http://127.0.0.1:8000/api/v1/files/newsimages'

  constructor(private http: Http) { }

  getTemporaryNewsImages(): Promise<NewsImageObject[]> {
    return this.http.get(this.newsImageUrl)
    .toPromise()
    .then(response => response.json().data as NewsImageObject[])
    .catch(this.handleError)
  }

  deleteNewsImage(url:string): Promise<NewsImageObject[]> {
    return this.http.delete(this.newsImageUrl+'/'+ url, {headers: this.headers})
    .toPromise()
    .then(() => null)
    .catch(this.handleError)
  }

  createNews(data: any): Promise<NewsObject> {
    return this.http
      .post(this.newsUrl, data, {headers: this.headers})
      .toPromise()
      .then(res => res.json().data as NewsObject)
      .catch(this.handleError)
  }

  private handleError(error: any): Promise<any> {
    //console.error('An error occurred', error)
    //return Promise.reject(error.message || error)

      return Promise.reject({
        'status' : error.status,
        'body' : typeof error._body !== 'undefined' ? JSON.parse(error._body) : null,
      })
  }
}
