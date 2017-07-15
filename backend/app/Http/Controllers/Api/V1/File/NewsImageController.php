<?php

namespace App\Http\Controllers\Api\V1\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use File;
use Storage;

use App\News;

use App\Helpers\FileHelpers;


class NewsImageController extends Controller
{
    public function __construct()
	{
        $this->FileHelpers = new FileHelpers;
        $this->image_news_temp_path = 'uploads/news-temp/';
        $this->image_news_path = 'uploads/news/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = $this->FileHelpers->readDir($this->image_news_temp_path);
        $result = [];
        foreach( $files as $f ){
            $fileName = explode('/', $f);
            $filename = $fileName[count($fileName)-1];
            $result[] = [
                'id' => null,
                'news_id' => null,
                'file_name' => $filename,
                'file_ext' => '.'.pathinfo($filename)['extension'],
                'small_url' => asset($f),
                'cover' => 0,
            ];
        }

        return response()->json([
            'data' => $result
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag()->toArray(), 400);
        }

        $image = $request->file;
        $extension = $image->getClientOriginalExtension();
        $file_name = time().'-'.rand(1,100).'.'.$extension;

        $image->move($this->image_news_temp_path, $file_name);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => null,
                'news_id' => null,
                'file_name' => $file_name,
                'file_ext' => '.'.pathinfo($file_name)['extension'],
                'small_url' => asset($this->image_news_temp_path.$file_name),
                'cover' => 0
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String  $file_name
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $file_name)
    {
        if( !file_exists( $this->image_news_temp_path.$file_name ) )
            abort(404);
        File::delete($this->image_news_temp_path.$file_name);
        return response()->json([
            'success' => true,
            'filename' => $file_name
        ]);
    }
}
