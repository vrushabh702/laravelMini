<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Response;
use DataTables;

abstract class Controller
{
    //
}
class TaskController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Task::select('*'))
            ->addColumn('action', 'task-action')
            ->addColumn('image', 'image')
            ->rawColumns(['action','image'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('tasks');
    }

    public function store(Request $request)
    {
        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);
        $taskId = $request->task_id;

        if ($files = $request->file('image')) {

           //delete old file
           \File::delete('public/task/'.$request->hidden_image);

           //insert new file
           $destinationPath = 'public/task/'; // upload path
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $image = "$profileImage";
        }
        $task = Task::find($taskId) ?? new Task();

          // Set the individual attributes
          $task->id = $taskId;
          $task->title = $request->title;
          $task->task_code = $request->task_code;
          $task->description = $request->description;
          $task->image = $image;
  
          // Save the task
          $task->save();
  
          return Response::json($task);
    }
    public function edit($id)
    {
        $where = array('id' => $id);
        $task  = Task::where($where)->first();

        return Response::json($task);
    }
    public function destroy($id)
    {
        $data = Task::where('id',$id)->first(['image']);
        \File::delete('public/task/'.$data->image);
        $task = Task::where('id',$id)->delete();

        return Response::json($task);
    }
}