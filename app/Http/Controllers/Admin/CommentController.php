<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\SystemsModuleType;
use App\Models\Alias;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Pages;
use App\Models\Product;
use App\Models\Videos;
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
        return abort(404);
    }

    public function list($type){
        check_admin_systems(SystemsModuleType::COMMENTS);

       $comment = new Comment();
       $class = $comment->find_class($type);
        if(!$class)
            return abort(404);

       $id = $comment->whereCommentType(get_class($class))->get()->pluck('comment_id');

       $comments = $class->with('comments')
           ->whereIn('id',$id)
           ->latest()
           ->get();

        return  view('Admin.Comment.index',compact('comments','type'));
    }
    public function detail($type,$id){
        check_admin_systems(SystemsModuleType::COMMENTS);
        switch ($type){
            case 'products':
                $class = new Product();
                break;
            case 'news':
                $class = new News();
                break;
            case 'pages':
                $class = new Pages();
                break;
            case 'videos':
                $class = new Videos();
                break;
            case 'gallerys':
                $class = new Gallery();
                break;
        }
        $comment = new Comment();
        $class = $comment->find_class($type);
        if(!$class)
            return abort(404);

        $model =  $class->find($id);

        $comments = $model->comments->load(['user','admin']);

        return  view('Admin.Comment.edit',compact('comments','model'));
    }
    public function destroys($type, $id){

        check_admin_systems(SystemsModuleType::COMMENTS);
        $comment = new Comment();
        $class = $comment->find_class($type);

        $model =  $class->find($id);
        $model->comments()->delete();

        return back()->with(['message' => 'Xóa bình luận thành công!']);
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
        check_admin_systems(SystemsModuleType::COMMENTS);

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
           $comment->user_id = Auth::id();
           $comment->admin_id = Auth::id();
           $comment->status =  1;
           $comment->parent_id  = $request->parent ? $request->parent : 0;
           $comment->note = $request->comment;
           $comment->save();

           if($request->reply){
               $comment = Comment::findOrFail($request->reply);
               $comment->update([
                   'status' => 1
               ]);
           }
           return back()->with(['message' => 'Trả lời thành công!']);
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
        check_admin_systems(SystemsModuleType::COMMENTS);
        $slug = Alias::findOrFail($id);
        $model = $slug->findModel($slug->type,$slug->type_id);

        $comments = $model->comments->load(['user','admin']);
        if(!$comments)
            return redirect()->route('admin.comments.index')->withErrors(['message' => 'Lỗi thông tin!']);

        return view('Admin.Comment.edit',compact('comments','model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        check_admin_systems(SystemsModuleType::COMMENTS);

        if($comment->hidden == 0){
            $comment->update([
                'hidden' => 1
            ]);
        }else{
            $comment->update([
                'hidden' => 0
            ]);
        }
        return back()->withInput()->with(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        check_admin_systems(SystemsModuleType::COMMENTS);
        $slug = Alias::findOrFail($id);
        $model = $slug->findModel($slug->type,$slug->type_id);

        $model->comments()->delete();

        return back()->with(['message' => 'Xóa bình luận thành công!']);
    }
}
