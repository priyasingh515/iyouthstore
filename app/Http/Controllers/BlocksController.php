<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\CgDistrict;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class BlocksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_blocks'])
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

        $query = Block::with(['country','state','district']);

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

        $blocks = $query->orderBy('created_at','desc')->paginate(15);

        $countries = Country::where('status',1)->get();
        $states    = State::where('status',1)->get();
        $districts = City::where('status',1)->get();

        return view(
            'backend.setup_configurations.blocks.index',
            compact(
                'blocks',
                'countries',
                'states',
                'districts',
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
        // dd($request->all());
        $request->validate([
            'name'        => 'required|string|max:255',
            // 'country_id'  => 'required|exists:countries,id',
            // 'state_id'    => 'required|exists:states,id',
            'district_id' => 'required|exists:cities,id',
        ]);
        $block = new Block;

        $block->name        = $request->name;
        // $block->country_id  = $request->country_id;
        // $block->state_id    = $request->state_id;
        $block->district_id = $request->district_id;
        $block->status      = 1;

        $block->save();

        flash(translate('Block has been inserted successfully'))->success();
        return back();
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit($id)
    {
        $block = Block::findOrFail($id);

        $countries = Country::where('status',1)->get();
        $states    = State::where('status',1)->get();
        $districts = City::where('status',1)->get();

        return view(
            'backend.setup_configurations.blocks.edit',
            compact('block','countries','states','districts')
        );
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, $id)
    {
        $block = Block::findOrFail($id);

        // $request->validate([
        //     'name'        => 'required|string|max:255',
        //     'country_id'  => 'required|exists:countries,id',
        //     'state_id'    => 'required|exists:states,id',
        //     'district_id' => 'required|exists:cities,id',
        // ]);

        $block->name        = $request->name;
        // $block->country_id  = $request->country_id;
        // $block->state_id    = $request->state_id;
        $block->district_id = $request->district_id;
        $block->status      = $request->status;

        $block->save();

        flash(translate('Block has been updated successfully'))->success();
        return redirect()->route('blocks.index');
    }

    // =========================================
    // DELETE
    // =========================================
    public function destroy($id)
    {
        Block::destroy($id);

        flash(translate('Block has been deleted successfully'))->success();
        return redirect()->route('blocks.index');
    }

    // =========================================
    // STATUS UPDATE
    // =========================================
    public function updateStatus(Request $request)
    {
        $block = Block::findOrFail($request->id);
        $block->status = $request->status;
        $block->save();

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
}