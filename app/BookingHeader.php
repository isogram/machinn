<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\Auth;

class BookingHeader extends Model
{
    /**
     * @var string
     */
    protected $table = 'booking_header';

    /**
     * @var array
     */
    protected $fillable = [
        'guest_id', 'room_plan_id', 'partner_id', 'type', 'checkin_date', 'checkout_date', 'adult_num','room_list', 'booking_code',
        'child_num', 'is_banquet', 'booking_status', 'payment_status', 'created_by', 'updated_by', 'grand_total', 'notes', 'void_reason', 'checkout',
        'banquet_time_id', 'banquet_event_id', 'banquet_event_name', 'audited'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * @param array $filter
     * @return mixed
     */
    public static function getBooking($filter = array()){
        $where = [];
        $payment_status = [1,2,3,4];

        if(isset($filter['room_number']) && $filter['room_number'] != null){
            $room = RoomNumber::where('room_number_code', $filter['room_number'])->first();
            $room_id = isset($room->room_number_id) ? $room->room_number_id : '';
            $where[] = ['booking_header.room_list', 'LIKE', $room_id];
        }

        if(isset($filter['status']) && $filter['status'] != 0) {
            $where[] = ['booking_header.booking_status', '=', $filter['status']];
        }

        if(isset($filter['checkout']) && $filter['checkout'] != 0) {
            $where[] = ['booking_header.checkout', '=', $filter['checkout']];
        }

        if(isset($filter['unpaid']) && $filter['unpaid'] != 0) {
            $payment_status = [1,2,4];
        }

        if(isset($filter['paid']) && $filter['paid'] != 0) {
            $payment_status = [2,3];
        }

        $guest = (isset($filter['guest'])) ? $filter['guest'] : '';

        $getBooking = DB::table('booking_header')
                        ->select(DB::raw('booking_header.booking_id,booking_header.guest_id, booking_code, room_list, first_name, room_plan_id, last_name, id_number, id_type, handphone, checkin_date, checkout_date,
                            (select count(*) from booking_room where booking_id = booking_header.booking_id) as room_num, booking_header.partner_id, partner_name, booking_header.type, booking_status,checkout,
                            (select total_payment from booking_payment where booking_id = booking_header.booking_id and type = 1 limit 1) as down_payment'))
                        ->join('guests', 'booking_header.guest_id', '=', 'guests.guest_id')
                        ->leftJoin('partners', 'booking_header.partner_id', '=', 'partners.partner_id')
                        ->orderBy('booking_header.booking_id', 'desc')
                        ->where($where)
                        ->whereIn('payment_status', $payment_status)
                        ->whereRaw("(last_name LIKE '%$guest%' OR first_name LIKE '%$guest%')")
                        ->paginate(config('app.limitPerPage'));

        $booking = array();
        foreach($getBooking as $row) {
            $booking[] = $row;
        }

        $data['booking'] = $booking;
        $data['link'] = $getBooking->links();

        return $data;
    }

    /**
     * @param $bookingId
     * @return mixed
     */
    public static function getBookingDetail($bookingId){
       $detail = DB::table('booking_header')
                    ->select(DB::raw('booking_header.type as booking_type, booking_code, room_list, booking_header.guest_id, banquet_time_id, banquet_event_id, banquet_event_name,
                    (select SUM(room_rate) from booking_room where booking_id = booking_header.booking_id group by booking_id) as total_room_rate, room_plan_id, partner_id, checkin_date, checkout_date, adult_num, child_num,
                    is_banquet, notes, title, id_type, id_number, first_name, last_name, guests.type as guest_type, birthdate, birthplace,
                    religion, gender, job, address, country_id, province_id, email, homephone, handphone, payment_method, booking_payment.type,
                    total_payment, card_type, card_number, bank, cc_type_id, settlement_id, card_name, card_expiry_month, card_expiry_year,
                    bank_transfer_recipient'))
                    ->where('booking_header.booking_id', $bookingId)
                    ->join('guests', 'booking_header.guest_id', '=', 'guests.guest_id')
                    ->leftJoin('booking_payment', 'booking_payment.booking_id', '=', 'booking_header.booking_id')
                    ->first();

        return $detail;
    }

    /**
     * @param $input
     * @param $guestId
     * @param null $existingId
     * @param string $source
     * @return array
     */
    public static function processHeader ($input, $guestId, $existingId = null, $source = 'booking') {
        $room_number = explode(',',$input['room_number']);
        $room_number = array_filter($room_number);

        $checkin = strtotime($input['checkin_date']);
        $checkout = strtotime($input['checkout_date']);
        $datediff = floor(abs($checkin - $checkout)) / (60 * 60 * 24);

        $getRoomPlan = RoomPlan::find($input['room_plan_id']);
        $cost = $getRoomPlan->room_plan_additional_cost;

        if($source == 'booking'){
            $payment_status = ($input['type'] == 1) ? 2 : 1;
        } else {
            $payment_status = 1;
        }

        $bookingHeader = [
            'guest_id'      => $guestId,
            'room_plan_id'  => $input['room_plan_id'],
            'partner_id'  => ($source == 'booking') ? $input['partner_id'] : 0,
            'type'      => ($source == 'booking') ? $input['type'] : 3, // 3 MEANS FROM CHECKIN
            'room_list' => implode(',', $room_number),
            'checkin_date'      => $input['checkin_date'],
            'checkout_date'      => $input['checkout_date'],
            'adult_num'      => isset($input['adult_num']) ? $input['adult_num'] : 2,
            'grand_total'   => $input['total_rates'] + ($datediff * count($room_number) * $cost),
            'child_num'      => isset($input['child_num']) ? $input['child_num'] : 0,
            'notes'      => $input['notes'],
            'is_banquet'      => $input['is_banquet'],
            'banquet_time_id' => isset($input['banquet_time_id']) ? $input['banquet_time_id'] : null,
            'banquet_event_id' => isset($input['banquet_event_id']) ? $input['banquet_event_id'] : null,
            'banquet_event_name' => isset($input['banquet_event_name']) ? $input['banquet_event_name'] : null,
            'booking_status'      => ($source == 'booking') ? 1 : 2,
            'payment_status'    => $payment_status,
            'created_by'        => Auth::id()
        ];

        if($existingId == null){
            $bookingHeader['booking_code'] = GlobalHelper::generateBookingId($guestId);
        }

        $bookingHeader = ($existingId == null) ? parent::create($bookingHeader) : parent::find($existingId)->update($bookingHeader);
        return $bookingHeader;
    }

    /**
     * @param string $status
     * @return mixed
     */
    public static function getBookingReport($status = 'all'){
        $where = [];

        $where[] = ['booking_header.type', '<>', 3];
        if($status != 'all') {
            $where[] = ['booking_header.booking_status', '=', $status];
        }

        $getBooking = DB::table('booking_header')
            ->select(DB::raw('booking_header.booking_id,booking_header.guest_id, booking_code, room_list, first_name, last_name, id_number, id_type, handphone, checkin_date, checkout_date,
                            DAY(checkout_date) - DAY(checkin_date) as total_night,
                            (select count(*) from booking_room where booking_id = booking_header.booking_id) as room_num, booking_header.partner_id, partner_name, booking_header.type, booking_status,
                            (select total_payment from booking_payment where booking_id = booking_header.booking_id and type = 1 limit 1) as down_payment, booking_header.created_at'))
            ->join('guests', 'booking_header.guest_id', '=', 'guests.guest_id')
            ->leftJoin('partners', 'booking_header.partner_id', '=', 'partners.partner_id')
            ->orderBy('booking_header.booking_id', 'desc')
            ->where($where)
            ->get();

        return $getBooking;
    }

    /**
     * @param $total_header
     * @param $total_paid
     * @param $total_extra
     * @return mixed
     */
    public static function getTotalUnpaid($total_header, $total_paid, $total_extra) {
        return $total_header - $total_paid + $total_extra;
    }

    /**
     * @return string
     */
    public static function setNoShowBooking(){
        $noshow = parent::where('checkin_date','<', date('Y-m-d'))->where('booking_status', '=', 1)->get();
        $arr = [];
        foreach($noshow as $key => $val){
            $arr[] = $val->booking_id;

            BookingHeader::find($val->booking_id)->update([
                'booking_status' => 3,
                'updated_by' => 0,
            ]);

            BookingRoom::where('booking_id', $val->booking_id)->update([
                'status' => 7,
                'updated_by' => 0
            ]);
        }

        return implode(',', $arr);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getBookingCode ($id){
        $data = parent::find($id);

        return $data->booking_code;
    }
}
