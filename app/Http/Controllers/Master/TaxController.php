<?php

namespace App\Http\Controllers\Master;

use App\Tax;
use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalHelper;

class TaxController extends Controller
{
    /**
     * @var
     */
    private $model;

    /**
     * @var
     */
    private $module;

    private $type;

    /**
     * @var string
     */
    private $parent;

    public function __construct()
    {
        $this->middleware('auth');

        $this->model = new Tax();

        $this->module = 'tax';

        $this->type = ['1' => 'Charged to Customer', '2' => 'Paid by Hotel'];

        $this->parent = 'payment';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!UserRole::checkAccess($subModule = 3, $type = 'read')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['model'] = $this->model;
        $rows = $this->model->paginate();
        $data['rows'] = $rows;
        return view("master.".$this->module.".index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!UserRole::checkAccess($subModule = 3, $type = 'create')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['type'] = $this->type;
        return view("master.".$this->module.".create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!UserRole::checkAccess($subModule = 3, $type = 'create')){
            return view("auth.unauthorized");
        }
        $this->validate($request,[
            'tax_name'  => 'required|max:75|min:3',
            'tax_type'  => 'required',
            'tax_percentage'  => 'required',
        ]);

        $this->model->create([
            'tax_name'   => $request->input('tax_name'),
            'tax_type'   => $request->input('tax_type'),
            'tax_percentage'   => $request->input('tax_percentage'),
        ]);

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successCreateData'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!UserRole::checkAccess($subModule = 3, $type = 'update')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['type'] = $this->type;
        $data['row'] = $this->model->find($id);
        return view("master.".$this->module.".edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!UserRole::checkAccess($subModule = 3, $type = 'update')){
            return view("auth.unauthorized");
        }
        $this->validate($request,[
            'tax_name'  => 'required|max:75|min:3'
        ]);

        $data = $this->model->find($id);

        $data->tax_name = $request->input('tax_name');
        $data->tax_type = 2; // charged to hotel
        $data->tax_percentage = $request->input('tax_percentage');

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successUpdateData'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id, $status) {
        if(!UserRole::checkAccess($subModule = 3, $type = 'update')){
            return view("auth.unauthorized");
        }
        $data = $this->model->find($id);

        if($status == 1){
            $active = 0;
        } else {
            $active = 1;
        }

        $data->tax_status = $active;

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successChangeStatus'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id) {
        if(!UserRole::checkAccess($subModule = 3, $type = 'delete')){
            return view("auth.unauthorized");
        }
        $this->model->find($id)->delete();
        $message = GlobalHelper::setDisplayMessage('success', __('msg.successDelete'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }
}
