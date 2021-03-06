<?php

namespace App\Http\Controllers\Back;

use App\BookingHeader;
use App\NightAudit;
use App\OutletTransactionHeader;
use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * @var string
     */
    private $parent;

    public function __construct()
    {
        $this->parent = 'night-audit';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function room (Request $request){
        if(!UserRole::checkAccess($subModule = 14, $type = 'read')){
            return view("auth.unauthorized");
        }
        $start = ($request->input('checkin_date')) ? $request->input('checkin_date') : date('Y-m-d', strtotime("-1 month"));
        $end = ($request->input('checkout_date')) ? $request->input('checkout_date') : date('Y-m-d');
        $status = ($request->input('status')) ? $request->input('status') : 0;

        $rows = NightAudit::getRoomAudit($start, $end, $status);

        $data = [
            'start'     => $start,
            'end'     => $end,
            'status'     => $status,
            'parent_menu'   => $this->parent,
            'rows'          => $rows
        ];
        return view('back.audit.room', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processRoom (Request $request){
        if(!UserRole::checkAccess($subModule = 14, $type = 'create')){
            return view("auth.unauthorized");
        }
        $audit = $request->input('audit');

        if($audit){
            foreach($audit as $val){
                NightAudit::create([
                    'type'          =>  1,
                    'audited_by'    => Auth::id(),
                    'booking_id'    => $val
                ]);

                BookingHeader::find($val)->update([
                    'audited'   => 1
                ]);
            }
        } else {
            $message = GlobalHelper::setDisplayMessage('warning', __('msg.warningAudit'));
            return redirect(route("back.night.room"))->with('displayMessage', $message);
        }

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successMakeAudit'));
        return redirect(route("back.night.room"))->with('displayMessage', $message);
    }

    /**
     * @param $bookingId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voidRoom ($bookingId){
        if(!UserRole::checkAccess($subModule = 14, $type = 'update')){
            return view("auth.unauthorized");
        }
        BookingHeader::find($bookingId)->update([
            'audited'   => 0
        ]);

        NightAudit::where('booking_id', $bookingId)->delete();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successVoidAudit'));
        return redirect(route("back.night.room"))->with('displayMessage', $message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function outlet (Request $request){
        if(!UserRole::checkAccess($subModule = 14, $type = 'read')){
            return view("auth.unauthorized");
        }
        $start = ($request->input('checkin_date')) ? $request->input('checkin_date') : date('Y-m-d', strtotime("-1 month"));
        $end = ($request->input('checkout_date')) ? $request->input('checkout_date') : date('Y-m-d');
        $status = ($request->input('status')) ? $request->input('status') : 0;

        $rows = NightAudit::getOutletAudit($start, $end, $status);

        $data = [
            'start'     => $start,
            'end'     => $end,
            'status'     => $status,
            'parent_menu'   => $this->parent,
            'rows'          => $rows
        ];
        return view('back.audit.outlet', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processOutlet (Request $request){
        if(!UserRole::checkAccess($subModule = 14, $type = 'create')){
            return view("auth.unauthorized");
        }
        $audit = $request->input('audit');

        if($audit){
            foreach($audit as $val){
                NightAudit::create([
                    'type'          =>  2,
                    'audited_by'    => Auth::id(),
                    'transaction_id'    => $val
                ]);

                OutletTransactionHeader::find($val)->update([
                    'audited'   => 1
                ]);
            }
        } else {
            $message = GlobalHelper::setDisplayMessage('warning', __('msg.warningAudit'));
            return redirect(route("back.night.room"))->with('displayMessage', $message);
        }

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successMakeAudit'));
        return redirect(route("back.night.outlet"))->with('displayMessage', $message);
    }

    /**
     * @param $transaction_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voidOutlet ($transaction_id){
        if(!UserRole::checkAccess($subModule = 14, $type = 'update')){
            return view("auth.unauthorized");
        }
        OutletTransactionHeader::find($transaction_id)->update([
            'audited'   => 0
        ]);

        NightAudit::where('transaction_id', $transaction_id)->delete();

        $message = GlobalHelper::setDisplayMessage('success', __('msg.successVoidAudit'));
        return redirect(route("back.night.outlet"))->with('displayMessage', $message);
    }
}
