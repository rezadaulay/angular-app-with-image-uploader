import { BrowserModule } from '@angular/platform-browser'
import { FormsModule, ReactiveFormsModule } from '@angular/forms'
import { NgModule } from '@angular/core'
import { HttpModule, Http,  XHRBackend, RequestOptions } from '@angular/http'
import { FileUploadModule } from 'ng2-file-upload'

import { AppComponent } from './app.component'
import { CKEDITOR } from './ckeditor.component'

import { NewsService } from './news.service'

@NgModule({
  declarations: [
    AppComponent,
    CKEDITOR
  ],
  imports: [
    BrowserModule,
    HttpModule,
    FormsModule,
    ReactiveFormsModule,
    FileUploadModule,
  ],
  providers: [
    NewsService
  ],
  bootstrap: [
    AppComponent
  ]
})
export class AppModule { }
