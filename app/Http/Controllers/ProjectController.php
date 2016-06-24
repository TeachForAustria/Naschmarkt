<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Project;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

/**
 * Class ProjectController
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{

    /**
     * Updates Project name and its folders
     *
     * @param $id, the id of the project
     * @param Request $request containing the information needed
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector to the updated project
     */
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

    /**
     * Edit project view.
     *
     * @param $id of the project to edit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Create a new Project
     *
     * @param Request $request containg the name
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector to the projects page
     */
    public function newProject(Request $request)
    {
        $new_pro_name = $request->input('newPro');
        if($new_pro_name != ''){
            $project = new Project();
            $project->owner_id = Auth::user()->id;
            $project->name = $new_pro_name;
            $project->save();
        }

        return redirect('/projects');
    }

    /**
     * returns view containing all projects
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProjects(){
        return view('project',[
            'projects' => Project::all()
        ]);
    }

    /**
     * Show a specific project
     *
     * @param $id of the project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showViewProjectView($id)
    {
        $project = Project::with('folders')->findOrFail($id);
        $project->save();

        return view('projects.view', [
            'project' => $project
        ]);
    }

    /**
     * Delete a project and it's folders
     *
     * @param $id of the project
     * @param Request $request for showing the information
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector to the projects page
     */
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

        return redirect('/projects');
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

    /**
     * Detach a Post form a Folder
     *
     * @param Folder $folder parent folder of the post
     * @param Post $post post to detach from the folder
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector back tot the edit page
     */
    public function detachPost(Folder $folder, Post $post){
        if(!Auth::user()->name == $folder->project->owner->name or !Auth::user()->is_staff){
            abort(403);
        }

        $folder->posts()->detach($post);

        return redirect('/project/'. $folder->project->id .'/edit');
    }

    /**
     * Delete a folder by id
     *
     * @param Folder $folder to delete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function deleteFolder(Folder $folder){
        if(!Auth::user()->name == $folder->project->owner->name or !Auth::user()->is_staff){
            abort(403);
        }

        $folder->delete();

        return redirect('/project/'. $folder->project->id .'/edit');
    }
}