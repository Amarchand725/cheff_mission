<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Auth;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:vehicle-list|vehicle-create|vehicle-edit|vehicle-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:vehicle-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:vehicle-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:vehicle-delete', ['only' => ['destroy']]);
    // }
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = Property::orderby('id' , 'desc')->where('id' , '>' , 0);
            if($request['search'] != ""){
                $query->where('heading', 'like', '%'. $request['search'] .'%');
            }
            if($request['status'] != 'All'){
                if($request['status']==2){
                    $request['status']== 0;
                }
            $query->where('status' , $request['status']);
            }
            $models= $query->paginate(10);
            return (string) view('admin.properties.search' , compact('models'));
        }
        $page_title = 'All Properties';
        $models = Property::orderby('id' , 'desc')->paginate(10);
        return view('admin.properties.index' , compact('models' , 'page_title'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Add Properties';
        return view('admin.properties.create' , compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=$request->validate([
            'heading' => 'required',
            'description' => 'required|max:1000',
            'image' => 'required',
        ]);

        $model =  new Property();
        if(isset($request->image)){
            $photo= date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/properties'), $photo);
            $model->image = $photo;
        }

        $model->created_by = Auth::user()->id;
        $model->slug = $request->slug;
        $model->heading = $request->heading;
        $model->description = $request->description;
        $model->room = $request->room;
        $model->bed = $request->bed;
        $model->bathroom = $request->bathroom;
        $model->price = $request->price;
        $model->rating = $request->rating;
        $model->save();

        return redirect()->route('property.index')->with('message' , 'Property Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $page_title = 'Edit Properties';
        $model = Property::where('slug' , $slug)->first();
        return view('admin.properties.edit' , compact('model' , 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $validator=$request->validate([
            'heading' => 'required',
            'description' => 'required|max:1000',
            'image' => 'required',
        ]);
        $update = Property::where('slug' , $slug);

        if(isset($request->image)){
            $photo= date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/properties') , $photo);
            $update->image = $photo;
        }

        $update->slug = $request->slug;
        $update->heading = $request->heading;
        $update->description = $request->description;
        $update->room = $request->room;
        $update->bed = $request->bed;
        $update->bathroom = $request->bathroom;
        $update->price = $request->price;
        $update->rating = $request->rating;
        $update->status = $request->status;
        $update->update();

        return redirect()->route('property.index')->with('message' , 'Property Update Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $model= Property::where('slug' , $slug)->first();
        if($model){
            $model->delete();
            return true;
        }else{
            return response()->json(['message' => 'Failed'],404);
        }
    }
}
