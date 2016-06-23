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

        if(!Auth::user()->name == $project->owner->name && !Auth::user()->is_staff){
            abort(403);
        }

        $project->name = $request->input('title');

        $folders = $project->folders->all();

        foreach($folders as $folder){

            $inputName = 'newpost' . $folder->id;
            $postNames = explode(',', $request->input($inputName));

            if($postNames[0] !== '') {
                foreach ($postNames as $name) {

                    $posts = Post::all();

                    foreach ($posts as $post) {
                        if($post->name === $name) {
                            $folder->posts()->save($post);
                            $folder->save();
                        }
                    }
                }
            }
        }

        $newfolders = explode(',', $request->input('newfolder'));
        if($newfolders[0] !== '') {
            foreach ($newfolders as $foldername) {
                $fold = new Folder();
                $fold->name = $foldername;
                $fold->save();
                $project->folders()->save($fold);
                $project->save();
            }
        }

        $project->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Deine &Auml;nderungen wurden erfolgreich gespeichert.'
        ]);


        return redirect('/project/' . $id);
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

        if($full_query !== null){
            $projects = $projects->where('name', $full_query);
        }

        $new_pro_name = $request->input('newPro');
        if($new_pro_name != ''){
            $project = new Project();
            $project->owner_id = Auth::user()->id;
            $project->name = $new_pro_name;
            $project->save();
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

        if(!Auth::user()== $projectToDelete->owner && !Auth::user()->is_staff){
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

    /**
     * @return array of all post names
     */
    public function postNames(){
        $names = array();
        $posts = Post::all();

        foreach ($posts as $post){
            array_push($names, $post->name);
        }

        return $names;
    }
}
