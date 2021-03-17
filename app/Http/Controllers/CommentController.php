<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $request->validate([
            'comment' => 'required',
            'slug' => 'required',
        ]);
        $comment = new Comment();
        $alias = Alias::whereAlias($request->slug)->first();
        if(!$alias)
            return back()->withInput()->withErrors(['message' => 'Đã có lỗi xảy ra. Vui lòng thủ lại!']);

           $model = $alias->findModel($alias->type,$alias->type_id);
           $comment->comment()->associate($model);
           $comment->user_id = Auth::check() ? Auth::id() : 0;
           $comment->admin_id = Auth::check() && Auth::user()->lever <= 2 ? Auth::id() : 0;
           $comment->status = Auth::check() && Auth::user()->lever <= 2 ? 1 : 0;
           $comment->parent_id  = $request->parent ? $request->parent : 0;
           $comment->note = $request->comment;
           $comment->save();

           if(Auth::check() && Auth::user()->lever <= 2)
               if($request->reply){
                   $comment = Comment::find($request->reply);
                   $comment->update([
                       'status' => 1
                   ]);
               }

           return back()->with(['message' => 'Cám ơn bạn đã để lại bình luận!']);
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
