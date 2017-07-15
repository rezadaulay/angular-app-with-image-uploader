# Simple Laravel & AngularJS Web App With Image Uploader Support

Example Of Simple [Laravel](https://laravel.com) & [AngularJS](https://angular.io/) Web App With Image Uploader Support.

## Setup
```
$ git clone  https://github.com/rezadaulay/angular-app-with-image-uploader.git
```
### Setup Laravel ( Api )
```
$ cd angular-app-with-image-uploader/backend
$ composer update
```

### Setup Angular ( Frontend )
```
$ cd angular-app-with-image-uploader/frontend
$ npm install
```
Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

## Explanation

### How Angular Handle Image Uploader
This App use [ng2-file-upload](https://github.com/valor-software/ng2-file-upload) directives.
~~~~
/* app.component.ts */

....

private uploader:FileUploader = new FileUploader({url: 'http://127.0.0.1:8000/api/v1/files/newsimages'})
ngOnInit(): void {
    ....
    /* upload image after add file */
    this.uploader.onAfterAddingFile = (item) => {item
      item.upload()
    }
    
    this.uploader.onBeforeUploadItem = (item) => {item
      item.withCredentials = false
    }
    
    /* push error message when upload failed */
    this.uploader.onErrorItem = (item, response) => {
      this.uploader_errors.push(JSON.parse(response).file)
      item.remove()
    }
    
    /* push news.image when upload succesed */
    this.uploader.onSuccessItem = (item, response) => {
      this.uploader_errors = []
      let image = JSON.parse(response).data
      if( !this.news.images.length )
        image.cover = true
      this.news.images.push(image)
      setTimeout(()=>{ this.uploader.removeFromQueue(item) }, 2000)      
    }
  }

/* app.component.html */
...
<!-- Uploader Directive see https://github.com/valor-software/ng2-file-upload -->
<input type="file" ng2FileSelect [uploader]="uploader" multiple  />
<!-- Uploader Error message -->
<div *ngIf="uploader_errors.length" class="alert alert-danger">
    <ul>
        <li *ngFor="let uploader_error of uploader_errors">
            {{ uploader_error }}
        </li>
    </ul>
</div>
...
<!-- Loop for image selected -->
<tr *ngFor="let item of uploader.queue">
...
<!-- Loop for image uploaded -->
<li *ngFor="let image of news.images ; let i = index">
~~~~


### How Laravel Handle Image Uploader
~~~~
/* NewsImageController.php */
...
public function store(Request $request){
        ...
        /* store angular image uploader to variable $image  */
        $image = $request->file;
        ...
}
~~~~

### License
The MIT License (see the [LICENSE](https://github.com/valor-software/ng2-file-upload/blob/master/LICENSE) file for the full text)