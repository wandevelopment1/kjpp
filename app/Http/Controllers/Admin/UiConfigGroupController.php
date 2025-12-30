<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UIConfigGroup;

class UiConfigGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.ui-config-group.index')->only('index');
        $this->middleware('can:admin.ui-config-group.create')->only('create','store');
        $this->middleware('can:admin.ui-config-group.edit')->only('edit','update');
        $this->middleware('can:admin.ui-config-group.delete')->only('destroy');
        $this->middleware('can:admin.ui-config-group.sort')->only('sort','updateOrder');
    }

    public function index(){
        $uiConfigGroups = UIConfigGroup::orderBy('order')->get();
        return view('admin.ui-config-group.index', compact('uiConfigGroups'));
    }

    public function create(){
        return view('admin.ui-config-group.form');
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $uiConfigGroup = new UiConfigGroup();
        $uiConfigGroup->title = $request->title;
        $uiConfigGroup->order = UiConfigGroup::max('order') + 1;
        $uiConfigGroup->save();

        return redirect()->route('admin.ui-config-group.index')->with('success', 'UI Config Group created successfully');
    }

    public function edit(UiConfigGroup $uiConfigGroup){
        return view('admin.ui-config-group.form', compact('uiConfigGroup'));
    }

    public function update(Request $request, UiConfigGroup $uiConfigGroup){
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $uiConfigGroup->title = $request->title;
        $uiConfigGroup->save();

        return redirect()->route('admin.ui-config-group.index')->with('success', 'UI Config Group updated successfully');
    }

    public function destroy(UiConfigGroup $uiConfigGroup){
        $uiConfigGroup->delete();
        return redirect()->route('admin.ui-config-group.index')->with('success', 'UI Config Group deleted successfully');
    }

    public function sort()
    {
        $uiConfigGroups = UiConfigGroup::orderBy('order', 'asc')->get();
        return view('admin.ui-config-group.sort', compact('uiConfigGroups'));
    }

    public function updateOrder(Request $request)
    {
        try {
            foreach ($request->order as $item) {
                UiConfigGroup::where('id', $item['id'])->update(['order' => $item['order']]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
