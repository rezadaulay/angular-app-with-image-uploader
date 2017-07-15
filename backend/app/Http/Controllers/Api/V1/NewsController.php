<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\News;
use App\NewsImage;
use App\Newscategory;
use App\Tag;

use Validator;
use File;
use App\Helpers\FileHelpers;

use Auth;

class NewsController extends Controller
{
    public function __construct()
	{
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag()->toArray(), 400);
        }
        \DB::beginTransaction();
		try{
            /* Insert New News */
            $news = News::create([
                'title' => $request->title,
                'content' => $request->content,
			]);

            if( count($request->images) ){   
                if(!is_dir( $this->image_news_path )){
                    File::makeDirectory( $this->image_news_path , 0755, true);
                }         
                foreach($request->images as $key => $image){
                    $news_image = $news->images()->save(
                        new NewsImage([
                            'file_name' => $image['file_name'],
                            'caption' => isset($image['caption'])? $image['caption'] : '',
                            'cover' => isset($image['cover']) && $image['cover'] ? 1 : 0 ,
                        ])
                    );
                    rename( $this->image_news_temp_path.$image['file_name'], $this->image_news_path.$image['file_name']);
                }
			}
			\DB::commit();
			return response()->json([
				'success' => true,
				'data' => $news,
			]);
        }
		catch(\Exception $e){
			\DB::rollback();
			return response()->json([
				'success' => false,
				'error' => \Config::get('app.debug') ? $e->getMessage() : 'Opss.. Seperti nya terjadi kesalahan,silahkan coba kembali.' ,
			], 500);
		}

        return $request->all();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
