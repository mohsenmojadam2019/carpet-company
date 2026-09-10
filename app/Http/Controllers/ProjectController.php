<?php
namespace App\Http\Controllers;
use App\Models\Project; use Illuminate\View\View;
class ProjectController extends Controller
{
    public function index():View { return view('projects.index',['projects'=>Project::where('is_active',true)->latest()->paginate(12)]); }
    public function show(Project $project):View { abort_unless($project->is_active,404);$project->load('media');return view('projects.show',compact('project')); }
}
