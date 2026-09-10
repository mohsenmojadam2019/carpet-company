<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Project; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Validation\Rule; use Illuminate\View\View;
class ProjectController extends Controller
{
    public function index():View { $this->guard('projects.view'); return view('admin.projects.index',['projects'=>Project::with('media')->latest()->paginate(20)]); }
    public function create():View { $this->guard('projects.manage'); return view('admin.projects.form',['project'=>new Project()]); }
    public function store(Request $request):RedirectResponse { $this->guard('projects.manage'); $project=Project::create($this->payload($request)); $this->syncMedia($request,$project); return redirect()->route('admin.projects.edit',$project)->with('success','پروژه ایجاد شد.'); }
    public function edit(Project $project):View { $this->guard('projects.manage'); $project->load('media'); return view('admin.projects.form',compact('project')); }
    public function update(Request $request,Project $project):RedirectResponse { $this->guard('projects.manage'); $project->update($this->payload($request,$project)); $this->syncMedia($request,$project); return back()->with('success','پروژه به‌روزرسانی شد.'); }
    public function destroy(Project $project):RedirectResponse { $this->guard('projects.manage'); $project->delete(); return redirect()->route('admin.projects.index')->with('success','پروژه حذف شد.'); }
    private function syncMedia(Request $request,Project $project):void { if($request->hasFile('cover'))$project->addMediaFromRequest('cover')->toMediaCollection('cover'); foreach($request->file('gallery',[]) as $file)$project->addMedia($file)->toMediaCollection('gallery'); foreach((array)$request->input('remove_media',[]) as $id)$project->media()->whereKey((int)$id)->first()?->delete(); }
    private function payload(Request $request,?Project $project=null):array { $data=$request->validate(['title'=>['required','string','max:190'],'slug'=>['nullable','string','max:190',Rule::unique('projects','slug')->ignore($project)],'excerpt'=>['nullable','string','max:500'],'content'=>['nullable','string'],'location'=>['nullable','string','max:190'],'year'=>['nullable','integer','min:1300','max:2200'],'cover'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:8192'],'gallery'=>['nullable','array','max:20'],'gallery.*'=>['image','mimes:jpg,jpeg,png,webp','max:8192']]); unset($data['cover'],$data['gallery']); $slug=$data['slug']?:Str::slug($data['title']); if($slug==='')$slug='project-'.Str::lower(Str::random(8)); return $data+['slug'=>$slug,'is_featured'=>$request->boolean('is_featured'),'is_active'=>$request->boolean('is_active',true)]; }
    private function guard(string $permission):void { abort_unless(request()->user()?->can($permission),403); }
}
