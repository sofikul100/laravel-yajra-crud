<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = "";
            $status = $request->has('status_filter') ? $request->status_filter : '';
            $startDate = null;
            $endDate = null;

            $query = Task::query();

            //======filtering date========//
            if ($request->has('date_filter')) {
                $dateFilter = $request->date_filter;

                switch ($dateFilter) {
                    case 'today':
                        $startDate = Carbon::today();
                        $endDate = Carbon::tomorrow();
                        break;
                    case 'yesterday':
                        $startDate = Carbon::yesterday();
                        $endDate = Carbon::today();
                        break;
                    case 'this_week':
                        $startDate = Carbon::now()->startOfWeek();
                        $endDate = Carbon::now()->endOfWeek();
                        break;
                    case 'last_week':
                        $startDate = Carbon::now()->subWeek()->startOfWeek();
                        $endDate = Carbon::now()->subWeek()->endOfWeek();
                        break;
                    case 'this_month':
                        $startDate = Carbon::now()->month();
                        break;
                    case 'last_month':
                        $startDate = Carbon::now()->subMonth();
                        $endDate = Carbon::now()->month();
                        break;
                    case 'this_year':
                        $startDate = Carbon::now()->startOfYear();
                        $endDate = Carbon::now()->endOfYear();
                        break;
                    case 'last_year':
                        $startDate = Carbon::now()->subYear()->startOfYear();
                        $endDate = Carbon::now()->subYear()->endOfYear();
                        break;
                }
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            //===end filtering date==========//


            //====status filter========//

            if ($status !== null) {
                $query->where('status', $status);
            }


            //=======end status filter=======//



            $tasks = $query->orderBy('id','DESC')->get();


            return DataTables::of($tasks)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'inactive') {
                        return '<span class="badge bg-danger">  Inactive</span>';
                    } elseif ($row->status == 'active') {
                        return '<span class="badge bg-success"> Active</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    if ($row->status == 'inactive') {
                        $actionBtn .= '<label class="switch">
                        <input type="checkbox" id="statusSwitch" data-id="'.$row->id.'">
                        <span class="slider"></span>
                    </label> &nbsp; &nbsp;'; 
                    }elseif($row->status == 'active') {
                        $actionBtn .= '<label class="switch">
                        <input type="checkbox" checked id="statusSwitch" data-id="'.$row->id.'">
                        <span class="slider"></span>
                    </label> &nbsp; &nbsp;'; 
                    }


                        $actionBtn .= '<a  class="btn btn-primary btn-sm edit_task"   data-id="' . $row->id . '"  data-bs-toggle="modal" data-bs-target="#edittask"><i
                        class="fa fa-pencil"></i> Edit</a>  &nbsp;&nbsp;';
                        $actionBtn .= '<a  class="btn btn-danger btn-sm"  data-id="' . $row->id . '" onclick="deleteTask(' . $row->id . ')"><i
                        class="fa fa-trash"></i> Delete</a> &nbsp;&nbsp;';

                       
                    
 
                    return $actionBtn;
                })



                ->rawColumns(['action','status',])
                ->make(true);
        } else {
            $tasks = Task::all();
            return view('dashboard', compact('tasks'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required',
        ]);

        $task = new Task();
        $task->name = $request->name;
        $task->save();
        return response()->json([
            'success' =>true,
            'message' => 'Task Added Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateTask(Request $request)
    {

        $request->validate([
            'name' =>'required',
        ]);

        $task = Task::findOrFail($request->id);
        $task->name = $request->name;
        $task->save();
        return response()->json([
           'success' =>true,
           'message' => 'Task Updated Successfully'
        ]);
         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([
            "success" => true,
            "message" => "Task Deleted Successfully"
        ]);
    }


    public function changeStatus(Request $request){
        $task = Task::find($request->task_id);
        if($task->status == 'inactive'){
            $task->status = 'active';
        }else{
            $task->status = 'inactive';
        } 
        
        $task->save();

        return response()->json(['success' => true,'message'=>'Task Status Change Successfully']);
    }
}
