<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PhotosController extends Controller
{
    public function index()
    {
        $photo = Photo::latest('created_at')->paginate(10);
        return View('photos.create')->with('photos', $photo);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('photos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $fileName = $input['fileName']->getClientOriginalName();
        $fileName = time()."@" . $fileName;

        $image = Image::make($input['fileName']->getRealPath());

        $image->resize(100, null , function ($constraint) {
            $constraint->aspectRatio();
        });

        $image->save(public_path() . '/images/' . $fileName);
        $path = 'images/' . $fileName;

        $photo = new Photo();
        $photo->path = 'images/' . $fileName;
        $photo->save();

        return redirect('/photos/')->with('status', 'ファイルアップロードの処理完了！');
    }

}