<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubDistrict;
use App\Models\Block;
use App\Models\CgDistrict;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class SubDistrictsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_sub_districts'])
            ->only('index', 'create', 'destroy');
    }

    // =========================================
    // LIST
    // =========================================
    public function index(Request $request)
    {
        $sort_name     = $request->sort_name;
        $sort_country  = $request->sort_country;
        $sort_state    = $request->sort_state;
        $sort_district = $request->sort_district;

        $query = SubDistrict::with(['country','state','district','block']);

        if ($sort_name) {
            $query->where('name', 'like', "%$sort_name%");
        }

        if ($sort_country) {
            $query->where('country_id', $sort_country);
        }

        if ($sort_state) {
            $query->where('state_id', $sort_state);
        }

        if ($sort_district) {
            $query->where('district_id', $sort_district);
        }

        $subDistricts = $query->orderBy('created_at','desc')->paginate(15);

        $countries = Country::where('status',1)->get();
        $states    = State::where('status',1)->get();
        $districts = City::where('status',1)->get();
        $blocks    = Block::where('status',1)->get();

        return view(
            'backend.setup_configurations.sub_districts.index',
            compact(
                'subDistricts',
                'countries',
                'states',
                'districts',
                'blocks',
                'sort_name',
                'sort_country',
                'sort_state',
                'sort_district'
            )
        );
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            // 'country_id'  => 'required|exists:countries,id',
            // 'state_id'    => 'required|exists:states,id',
            'district_id' => 'required|exists:cities,id',
            'block_id'    => 'required|exists:blocks,id',
        ]);

        $subDistrict = new SubDistrict;

        $subDistrict->name        = $request->name;
        // $subDistrict->country_id  = $request->country_id;
        // $subDistrict->state_id    = $request->state_id;
        $subDistrict->district_id = $request->district_id;
        $subDistrict->block_id    = $request->block_id;
        $subDistrict->status      = 1;

        $subDistrict->save();

        flash(translate('Sub District has been inserted successfully'))->success();
        return back();
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit($id)
    {
        $subDistrict = SubDistrict::findOrFail($id);

        $countries = Country::where('status',1)->get();
        $states    = State::where('status',1)->get();
        $districts = City::where('status',1)->get();
        $blocks    = Block::where('district_id', $subDistrict->district_id)->get();

        return view(
            'backend.setup_configurations.sub_districts.edit',
            compact('subDistrict','countries','states','districts','blocks')
        );
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, $id)
    {
        $subDistrict = SubDistrict::findOrFail($id);

        $subDistrict->name        = $request->name;
        // $subDistrict->country_id  = $request->country_id;
        // $subDistrict->state_id    = $request->state_id;
        $subDistrict->district_id = $request->district_id;
        $subDistrict->block_id    = $request->block_id;
        $subDistrict->status      = $request->status;

        $subDistrict->save();

        flash(translate('Sub District has been updated successfully'))->success();
        return redirect()->route('subdistricts.index');
    }

    // =========================================
    // DELETE
    // =========================================
    public function destroy($id)
    {
        SubDistrict::destroy($id);

        flash(translate('Sub District has been deleted successfully'))->success();
        return redirect()->route('subdistricts.index');
    }

    // =========================================
    // STATUS UPDATE
    // =========================================
    public function updateStatus(Request $request)
    {
        $subDistrict = SubDistrict::findOrFail($request->id);
        $subDistrict->status = $request->status;
        $subDistrict->save();

        return 1;
    }

    // =========================================
    // AJAX LOADERS
    // =========================================
    public function getStates(Request $request)
    {
        return State::where('country_id',$request->country_id)
                    ->where('status',1)
                    ->get(['id','name']);
    }

    public function getDistricts(Request $request)
    {
        return City::where('state_id',$request->state_id)
                         ->where('status',1)
                         ->get(['id','name']);
    }

    public function getBlocks(Request $request)
    {
        return Block::where('district_id',$request->district_id)
                    ->where('status',1)
                    ->get(['id','name']);
    }
}