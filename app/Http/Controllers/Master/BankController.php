<?php

namespace App\Http\Controllers\Master;

use App\Bank;
use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalHelper;

class BankController extends Controller
{
    /**
     * @var
     */
    private $model;

    /**
     * @var
     */
    private $module;

    public function __construct()
    {
        $this->middleware('auth');

        $this->model = new Bank();

        $this->module = 'bank';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        return view("master.".$this->module.".create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'bank_name'  => 'required|max:75|min:3'
        ]);

        $this->model->create([
           'bank_name'   => $request->input('bank_name')
        ]);

        $message = GlobalHelper::setDisplayMessage('success', 'Success to save new data');
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
        $this->validate($request,[
            'bank_name'  => 'required|max:75|min:3'
        ]);

        $data = $this->model->find($id);

        $data->bank_name = $request->input('bank_name');

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', 'Success to update data');
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
        $data = $this->model->find($id);

        if($status == 1){
            $active = 0;
        } else {
            $active = 1;
        }

        $data->bank_status = $active;

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', 'Success to change status of '.$data->bank_name);
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }
}
