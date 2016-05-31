<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Project;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class ProjectController extends Controller
{

    public function update($id, Request $request)
    {
        $project = Project::findOrFail($id);

        if(!(Auth::user()->name == $project->owner->name or !Auth::user()->is_staff)){
            abort(403);
        }

        $project->name = $request->input('title');

        $folders = $project->folders;

        $current = 1;

        while($current <= $project->folders->count()){
            $postNames = explode(',', $request->input('newpost'.$current));
            $folder = $folders->find($current);

            foreach ($postNames as $name){
                $posts = Post::all()->where('name', $name );

                foreach($posts as $post) {
                    $folder->posts()->save($post);
                }
            }

            $current++;
        }

        $newfolders = explode(',', $request->input('newfolder'));
        
        foreach ($newfolders as $foldername){
            $folder = new Folder();
            $folder->name = $foldername;
            $folder->save();
            $project->folders()->save($folder);
        }

        $project->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Deine &Auml;nderungen wurden erfolgreich gespeichert.'
        ]);

        return redirect('project/' . $id);
    }

    public function showEditProjectView($id)
    {
        $project = Project::with('folders')->findOrFail($id);

        if(!(Auth::user()->name == $project->owner->name or Auth::user()->is_staff)) {
            abort(403);
        }

        return view('projects.edit', [
            'project' => $project
        ]);
    }

    public function showProjectsView(Request $request)
    {
        $full_query = $request->input('projectQuery');

        $projects = Project::all();

        if($full_query !== ''){
            $projects = $projects->where('name', $full_query);
        }

        return view('project', [
            'projects' => $projects
        ]);
    }

    public function showViewProjectView($id)
    {
        $project = Project::with('folders')->findOrFail($id);
        $project->save();

        return view('projects.view', [
            'project' => $project
        ]);
    }

    public function deleteProject($id, Request $request)
    {

        $projectToDelete = Project::findOrFail($id);

        if(!Auth::user()->name == $projectToDelete->owner->name or !Auth::user()->is_staff){
            abort(403);
        }

        $folders = $projectToDelete->folders;

        foreach ($folders as $folder){
            $folder->delete();
        }

        $projectToDelete->delete();

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Dein Project wurde erfolgreich gel&ouml;scht.'
        ]);

        return redirect('project');
    }

}

?>