import {Component, EventEmitter, Input, Output, AfterViewInit, OnChanges, SimpleChanges, SimpleChange} from '@angular/core'

@Component({
  selector: 'CKEDITOR',
  template: `<textarea name="{{ targetId }}" id="{{ targetId }}" rows="{{ rows }}" cols="{{ cols }}">{{ value }}</textarea>`
})
export class CKEDITOR implements AfterViewInit {

    @Input('id') targetId: string
    @Input('model') targetModel: any
    @Input('row') rows: string = '10'
    @Input('cols') cols: string = '10'
    @Input('type') type: string = 'default' 
    @Input('value') value: string = null

    @Output() onChange = new EventEmitter<string>();
    private editor = null
    private ckeditorReady = false

    ngAfterViewInit(){
      this.editor = window['CKEDITOR']['replace']( this.targetId );

      var base = this;
      this.editor.on('change', function() {
          base.onChange.emit(base.editor.getData());
      });
      this.ckeditorReady = true
    }
    ngOnChanges(changes: SimpleChanges) {
      const tvalue: SimpleChange = changes.value;
      //console.log('prev value: ', tvalue.previousValue);
      //console.log('got value: ', tvalue.currentValue);
      if( this.ckeditorReady && tvalue.currentValue === null){
        console.log('ckeditorReady')
        this.editor.setData('')
      }
    }
}