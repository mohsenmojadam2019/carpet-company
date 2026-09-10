<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\MediaAsset; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\View\View; use Spatie\MediaLibrary\MediaCollections\Models\Media;
class MediaController extends Controller
{
    public function index():View { $this->guard('media.view'); return view('admin.media.index',['media'=>Media::latest()->paginate(30)]); }
    public function store(Request $request):RedirectResponse { $this->guard('media.manage'); $data=$request->validate(['files'=>['required','array','max:20'],'files.*'=>['file','mimes:jpg,jpeg,png,webp,pdf','max:12288']]); foreach($data['files'] as $file){$asset=MediaAsset::create(['title'=>pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME)]);$asset->addMedia($file)->toMediaCollection('library');} return back()->with('success','فایل‌ها در مدیا لایبرری ذخیره شدند.'); }
    public function destroy(Media $medium):RedirectResponse { $this->guard('media.manage'); $medium->delete(); return back()->with('success','فایل حذف شد.'); }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
