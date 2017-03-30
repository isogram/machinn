<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'Master\RoomNumberController@index');

Route::get('front/guest/checkin-report', 'Front\GuestController@checkinReport')->name("guest.checkin");
Route::get('front/guest/statistic', 'Front\GuestController@statistic')->name("guest.statistic");
Route::post('ajax/searchGuest', 'Front\AjaxController@searchGuest')->name("ajax.searchGuest");
Route::resource('front/guest', 'Front\GuestController');
Route::resource('front/staff', 'Front\StaffController');

Route::get('ajax/searchProvince', 'Front\AjaxController@searchProvince')->name("ajax.searchProvince");
Route::get('ajax/searchRoom', 'Front\AjaxController@searchRoom')->name("ajax.searchRoom");
Route::get('ajax/getTotalRoomRates', 'Front\AjaxController@getTotalRoomRates')->name("ajax.getTotalRoomRates");

Route::post('front/booking/void/{bookingId}', 'Front\BookingController@voidBooking')->name("booking.void");
Route::get('front/booking/report', 'Front\BookingController@report')->name("booking.report");
Route::get('front/booking/showdownpayment/{bookingId}', 'Front\BookingController@showDownPayment')->name("booking.showdownpayment");
Route::resource('front/booking', 'Front\BookingController');

Route::get('front/checkin', 'Front\CheckinController@create')->name('checkin.index');
Route::get('front/checkin/book/{bookingId}', 'Front\CheckinController@book')->name('checkin.book');
Route::post('front/checkin', 'Front\CheckinController@store')->name('checkin.store');

Route::get('master/contact-group/change-status/{id}/{status}', 'Master\ContactGroupController@changeStatus')->name('contact-group.change-status');
Route::resource('master/contact-group', 'Master\ContactGroupController');

Route::get('front/contact/change-status/{id}/{status}', 'Master\ContactController@changeStatus')->name('contact.change-status');
Route::resource('front/contact', 'Master\ContactController');

Route::get('front/logbook/change-status/{id}/{status}', 'Master\LogbookController@changeStatus')->name('logbook.change-status');
Route::resource('front/logbook', 'Master\LogbookController');

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');
