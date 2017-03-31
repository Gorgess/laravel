<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Article;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('articles')->orderby('updated_at','desc')->paginate(5);
        return view('admin/article/index', ['Articles' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/article/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' =>  'required|unique:articles|max:255',
            'body'  =>  'required',
        ]);
        $articleDb = new Article;
        $articleDb->title = $request->get('title');
        $articleDb->body = $request->get('body');
        $articleDb->user_id = Auth::guard('admin')->user()->id;
        if($articleDb->save()){
            return redirect('admin/article');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        return view('admin/article/edit',['article'=>$article]);
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
        $this->validate($request,[
            'title' => 'required|unique:articles,title,' . $id . '|max:255',
            'body'  =>  'required',
        ]);
        $articleDb = Article::findOrFail($id);
        $articleDb->title = $request->get('title');
        $articleDb->body = $request->get('body');
        $articleDb->user_id = Auth::guard('admin')->user()->id;

        if($articleDb->save()){
            return redirect('admin/article');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Article::find($id)->delete()){
            return redirect('admin/article');
        } else {
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }
}
