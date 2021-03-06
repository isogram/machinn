<?php

namespace App\Http\Controllers\Master;

use App\BookingRoom;
use App\PropertyFloor;
use App\RoomNumber;
use App\RoomType;
use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\App;

class RoomNumberController extends Controller
{

    /**
     * @var
     */
    private $model;

    /**
     * @var
     */
    private $module;

    /**
     * @var
     */
    private $floor;

    /**
     * @var
     */
    private $type;

    /**
     * @var string
     */
    private $parent;

    public function __construct()
    {
        $this->middleware('auth');

        $this->model = new RoomNumber();

        $this->module = 'room-number';

        $this->floor = PropertyFloor::where('property_floor_status', 1)->get();

        $this->type = RoomType::where('room_type_status', 1)->get();

        $this->parent = 'rooms';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!UserRole::checkAccess($subModule = 1, $type = 'read')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['status'] = config('app.roomStatus');
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
        if(!UserRole::checkAccess($subModule = 1, $type = 'create')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['type'] = $this->type;
        $data['floor'] = $this->floor;
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
        if(!UserRole::checkAccess($subModule = 1, $type = 'create')){
            return view("auth.unauthorized");
        }
        $this->validate($request,[
            'room_number_code'  => 'required|max:75|min:3|unique:room_numbers',
            'room_type_id'  => 'required|numeric',
            'room_floor_id'  => 'required|numeric',
        ]);

        $this->model->create([
            'room_number_code'   => $request->input('room_number'),
            'room_type_id'   => $request->input('room_type_id'),
            'room_floor_id'   => $request->input('room_floor_id')
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
        if(!UserRole::checkAccess($subModule = 1, $type = 'update')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = $this->parent;
        $data['type'] = $this->type;
        $data['floor'] = $this->floor;
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
        if(!UserRole::checkAccess($subModule = 1, $type = 'update')){
            return view("auth.unauthorized");
        }
        $this->validate($request,[
            'room_number_code'  => 'required|max:75|min:3|unique:room_numbers',
            'room_type_id'  => 'required|numeric',
            'room_floor_id'  => 'required|numeric',
        ]);

        $data = $this->model->find($id);

        $data->room_number_code = $request->input('room_number_code');
        $data->room_type_id = $request->input('room_type_id');
        $data->room_floor_id = $request->input('room_floor_id');

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
        if(!UserRole::checkAccess($subModule = 1, $type = 'update')){
            return view("auth.unauthorized");
        }
        $data = $this->model->find($id);

        $data->room_number_status = $status;

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successChangeStatus'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewRoom (Request $request){
        if(!UserRole::checkAccess($subModule = 9, $type = 'read')){
            return view("auth.unauthorized");
        }
        $data['parent_menu'] = 'room-transaction';
        $start = ($request->input('checkin_date')) ? $request->input('checkin_date') : date('Y-m-d');
        $end = ($request->input('checkout_date')) ? $request->input('checkout_date') : date('Y-m-d', strtotime("+14 days"));

        $datediff = floor(abs(strtotime($start) - strtotime($end))) / (60 * 60 * 24);

        $room = BookingRoom::getAllRoomBooked($start, $end);

        $modifiedKey = array();
        foreach($room as $key => $val){
            $modifiedKey[$val->room_number_id.':'.$val->room_transaction_date] = $val;
        }

        $data['room'] = $modifiedKey;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['date_diff'] = $datediff;
        $data['room_type'] = RoomType::where('room_type_status', 1)->get();

        return view("master.".$this->module.".view", $data);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id) {
        if(!UserRole::checkAccess($subModule = 1, $type = 'delete')){
            return view("auth.unauthorized");
        }
        $this->model->find($id)->delete();
        $message = GlobalHelper::setDisplayMessage('success', __('msg.successDelete'));
        return redirect(route($this->module.".index"))->with('displayMessage', $message);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function houseKeep(){
        if(!UserRole::checkAccess($subModule = 9, $type = 'read')){
            return view("auth.unauthorized");
        }
        $checkin = date('Y-m-d');
        $checkout = date('Y-m-d', strtotime('+1 day'));
        $getRoom = RoomNumber::getRoomAvailable($checkin, $checkout, $filter = array());
        $mod = [];

        foreach($getRoom as $key => $val){
            $mod[$val->room_type_name]['floor'][$val->property_floor_name][] = (array)$val;
        }

        $html = GlobalHelper::generateHTMLHouseKeeping($mod);
        $data['html'] = $html;
        return view('house.dashboard',$data);
    }

    /**
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeHkStatus($id, $status) {
        if(!UserRole::checkAccess($subModule = 11, $type = 'update')){
            return view("auth.unauthorized");
        }
        $data = $this->model->find($id);

        $data->hk_status = $status;

        $data->save();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successChangeStatus'));
        return redirect(route("house.dashboard"))->with('displayMessage', $message);
    }
}
