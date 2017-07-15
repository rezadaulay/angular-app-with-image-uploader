import { NewsImageObject } from './newsImage.object'

export class NewsObject {
  id: number = null
  title: string = null
  content: string = null
  images: NewsImageObject[] = []
}
