import { Component, OnInit } from '@angular/core'
import { CKEDITOR } from './ckeditor.component'
import { FileUploader } from 'ng2-file-upload'

import { NewsObject } from './news.object'
import { NewsImageObject } from './newsImage.object'

import { NewsService } from './news.service'

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {constructor(
  private newsService: NewsService) {
  }
  
  private news:NewsObject
  private uploader:FileUploader = new FileUploader({url: 'http://127.0.0.1:8000/api/v1/files/newsimages'})

  private uploader_errors = []
  private savingData = false

  private onChangeContent(val: string) {
    this.news.content = val
  }

  private createNews(){
    this.savingData = true
    let errorMessages = []
    this.newsService.createNews(this.news)
    .then(news => {
      this.savingData = false
      alert('Data Saved')
    })
    .catch((error) => {
      this.savingData = false
      console.log(error.body)
      alert('Data Failed To Save')
    })
  }
  
  private buttonImageClass(isCover): string {
    if( isCover )
      return 'btn-primary';
    else 
      return 'btn-default';
  }

  private selectACover(image): void {
    for(let i=0; i < this.news.images.length; i++)
      this.news.images[i].cover = false
    image.cover = true
  }

  private onChangeImageContent(val: string, model: any) {
    model.caption = val
  }

  private getTemporaryNewsImages(): void {
    this.newsService
        .getTemporaryNewsImages()
        .then(newsImages => {
          let images = [];
          for(let i=0; i < newsImages.length; i++){
            this.news.images.push(newsImages[i])
          }
        });
        
    
  }
  private deleteNewsImage(newsImage: NewsImageObject): void {
    if (confirm('Hapus gambar ini ?')) {
      this.newsService
      .deleteNewsImage(newsImage.file_name)
      .then(() => {
        this.news.images = this.news.images.filter(h => h !== newsImage)
      });
    }
  }

  ngOnInit(): void {
    this.news = {
      id : null,
      title : null,
      content :null,
      images :[]
    }

    this.getTemporaryNewsImages()

    this.uploader.onAfterAddingFile = (item) => {item
      item.upload()
    }

    this.uploader.onBeforeUploadItem = (item) => {item
      item.withCredentials = false
    }
    
    this.uploader.onErrorItem = (item, response) => {
      this.uploader_errors.push(JSON.parse(response).file)
      item.remove()
    }
    this.uploader.onSuccessItem = (item, response) => {
      this.uploader_errors = []
      let image = JSON.parse(response).data
      if( !this.news.images.length )
        image.cover = true
      this.news.images.push(image)
      setTimeout(()=>{ this.uploader.removeFromQueue(item) }, 2000)      
    }
  }
}
