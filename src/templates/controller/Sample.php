<?php

namespace [current_name_space];

use App\Http\Controllers\Controller;
//additional_namespaces//
use [namespace_model];
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class [name_table]Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
{
    //
    $request_all=request()->all();

    $route_name=Route::currentRouteName();
    if(isset($request_all['id']) and is_array($request_all['id'])){
        [name_table]::query()->whereIn("id",$request_all['id'])->delete();
        return redirect()->route($route_name);
    }

    $[name_table_little]=[name_table]::query()->where(function($query) use ($request_all){
        if(isset($request_all['enable']) and $request_all['enable']==1){
            $query->where("enable",1);
        }
        if(isset($request_all['enable']) and $request_all['enable']==2){
            $query->where("enable",0);
        }

        if(isset($request_all['data_type']) and $request_all['data_type']==1 and isset($request_all['date_create_from'])){
            $query->where('created_at', '>=', date('Y-m-d',strtotime($request_all['date_create_from'])).' 00:00:00');
            $query->where('created_at', '<=', date('Y-m-d',strtotime($request_all['date_create_from'])).' 23:59:00');

        }

        if(isset($request_all['data_type']) and $request_all['data_type']==3 and isset($request_all['date_create_from'])  and isset($request_all['date_create_to'])){
            $query->where('created_at', '>=', date('Y-m-d',strtotime($request_all['date_create_from'])).' 00:00:00');
            $query->where('created_at', '<=', date('Y-m-d',strtotime($request_all['date_create_to'])).' 23:59:00');

        }


    });
    if(isset($request_all['orderby']) and $request_all['orderby']=="desc"){
        $[name_table_little] = $[name_table_little]->orderByDesc("sort");
    }else {
        $request_all['orderby']="asc";
        $[name_table_little] = $[name_table_little]->orderBy("sort");
    }

    $[name_table_little]=$[name_table_little]->paginate(10)->appends(request()->query());

    $data=array();
    $data['[name_table_little]']=$[name_table_little];
    $data['request_all']=$request_all;

    return view('[VIEW_INDEX]', $data)
        ->with('i', (request()->input('page', 1) - 1) * 5);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data_create=array();
        //data_create//
        return view('[VIEW_CREATE]',$data_create);
    }

    public function  clone_object($id){
    $[name_table_little]=[name_table]::query()->find($id);
    if(!isset($[name_table_little])){
        return back()->withErrors(["Не найден обьект для клона"]);
    }
    $new_obj=$[name_table_little]->replicate();
    $new_obj->save();

    return back()->with("success","Клонирование прошло успешно!");
}
    public function change_position(){

    $all=request()->all();


    if(!(isset($all['current']) and isset($all['replace']) and is_numeric($all['current']) and is_numeric($all['replace']))){
        return 0;
    }
    $current=[name_table]::query()->where("sort",$all['current'])->first();
    $replace=[name_table]::query()->where("sort",$all['replace'])->first();
    if(!$current){
        return 0;
    }
    $current->sort=$all['replace'];
    $current->save();
    if($replace){
        $replace->sort=$all['current'];
        $replace->save();
    }
    return 1;
}
    public function toogle($id){
    $[name_table_little]=[name_table]::query()->find($id);
    $value=request()->get("val");
    if(!$value){
        $value=0;
    }
    if($[name_table_little]){
        $[name_table_little]->enable=$value;
        $[name_table_little]->save();
    }
    return  $[name_table_little]->enable;

}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // custom error
        //  return response()->json(['errors' => ['email' => ['The email is invalid.']]], 422);
        $validated=$request->validate([
            [REQUEST_VALIDATE_CREATE]

        ]);

        $[name_table_little]=[name_table]::create($validated);
        $[name_table_little]->sort=$[name_table_little]->id;


        //store//

          $after_save=request()->post("after_save");
        if(isset($after_save) and $after_save=="edit"){

            return array('link'=>route('[ROUTE_EDIT]', $[name_table_little]->id));
        }else {
            return array('link'=>route('[ROUTE_INDEX]'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  [namespace_model]  $[name_table_little]
     * @return \Illuminate\Http\Response
     */
    public function show([name_table] $[name_table_little])
    {
        //
        return view('[VIEW_SHOW]', compact('[name_table_little]'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  [namespace_model]  $[name_table_little]
     * @return \Illuminate\Http\Response
     */
    public function edit([name_table] $[name_table_little])
    {
        //
        $data_edit=array();
        $data_edit['[name_table_little]']=$[name_table_little];
        //data_edit//
        return view('[VIEW_EDIT]', $data_edit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  [namespace_model]  $[name_table_little]
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, [name_table] $[name_table_little])
    {
        //
        // custom error
        //  return response()->json(['errors' => ['email' => ['The email is invalid.']]], 422);
        $validated=$request->validate([
           [REQUEST_VALIDATE_UPDATE]


        ]);
        $[name_table_little]->update($validated);
        //update//


        $after_save=request()->post("after_save");
        if(isset($after_save) and $after_save=="edit"){

            return array('link'=>route('[ROUTE_EDIT]', $[name_table_little]->id));
        }else {
            return array('link'=>route('[ROUTE_INDEX]'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  [namespace_model]  $[name_table_little]
     * @return \Illuminate\Http\Response
     */
    public function destroy([name_table] $[name_table_little])
    {
        //
        $[name_table_little]->delete();

        return redirect()->route('[ROUTE_INDEX]')
            ->with('success', 'Document deleted successfully');
    }
}
