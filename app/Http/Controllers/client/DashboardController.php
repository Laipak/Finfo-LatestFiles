<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDashboard()
    {
        return view('client.dashboard.pages.dashboard');
    }

    public function getAllFinan()
    {
        return view('client.dashboard.pages.all-financial-highlights');
    }

    public function getFinanHighlight()
    {
        return view('client.dashboard.pages.financial-highlights');
    }

    public function getFinanHighlightEdit()
    {
        return view('client.dashboard.pages.financial-highlights-edit');
    }

    public function getAddInvesterCalendar()
    {
        return view('client.dashboard.pages.add-investor-relationship-calendar');
    }

    public function getEditInvesterCalendar()
    {
        return view('client.dashboard.pages.edit-investor-calendar');
    }

    public function getPricingCharts()
    {
        return view('client.dashboard.pages.pricing-charts');
    }

    public function getEditPricingCharts()
    {
        return view('client.dashboard.pages.edit-pricing-charts');
    }

    public function getMediaAccess()
    {
        return view('client.dashboard.pages.media-access');
    }

    public function getMediaApproval()
    {
        return view('client.dashboard.pages.media-approval');
    }

    public function getEditMediaAccess()
    {
        return view('client.dashboard.pages.edit-media-access');
    }

    public function getAllEvent()
    {
        return view('client.dashboard.pages.all-event');
    }
}
