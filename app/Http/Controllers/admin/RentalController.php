<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Auth;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:vehicle-list|car-create|car-edit|car-delete', ['only' => ['index','store']]);
         $this->middleware('permission:car-create', ['only' => ['create','store']]);
         $this->middleware('permission:car-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:car-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = Rental::orderby('id', 'desc')->where('id', '>', 0);
            if($request['search'] != ""){
                $query->where('name', 'like', '%' .$request['search'].'%');
            }
            if($request['status'] != 'All'){
                if($request['status']==2){
                    $request['status'] = 0;
                }
                $query->where('status', $request['status']);
            }
            $models = $query->paginate(10);
            return (string) view('admin.rental.search', compact('models'));
        }
        $page_title = 'All Rentals';
        $models = Rental::orderby('id', 'desc')->paginate(10);
        return View('admin.rental.index', compact("models","page_title"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        {
            $page_title = 'Add Rental';
            return View('admin.rental.create', compact('page_title'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* $validator = $request->validate([
            'title' => 'required|max:100',
            'description' => 'required|max:1000',
        ]); */

        $model = new Rental();

        if (isset($request->image)) {
            $photo = date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/rental'), $photo);
            $model->image = $photo;
        }

        $model->created_by = Auth::user()->id;
        $model->name = $request->name;
        $model->slug = \Str::slug($request->name);
        $model->seat = $request->seat;
        $model->short_description = $request->short_description;
        $model->description = $request->description;
        $model->color = $request->color;
        $model->rent = $request->rent;
        $model->save();

        return redirect()->route('rental.index')->with('message', 'Rental Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Rental $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $page_title = 'Edit Rental';
        $model = Rental::where('slug', $slug)->first();
        return View('admin.rental.edit', compact('model','page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$slug)
    {

      /*   $validator = $request->validate([
            'title' => 'required|max:100',
            'description' => 'required|max:1000',
        ]); */

        $update = Rental::where('slug', $slug)->first();

        if (isset($request->image)) {
            $photo = date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/rental'), $photo);
            $update->image = $photo;
        }

        $update->name = $request->name;
        $update->slug = \Str::slug($request->name);
        $update->seat = $request->seat;
        $update->short_description = $request->short_description;
        $update->description = $request->description;
        $update->color = $request->color;
        $update->rent = $request->rent;
        $update->status = $request->status;
        $update->update();


        return redirect()->route('rental.index')->with('message', 'Rental Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $model = Rental::where('slug', $slug)->first();
        if ($model) {
            $model->delete();
            return true;
    }   else {
            return response()->json(['message' => 'Failed '], 404);
            }
    }
}
