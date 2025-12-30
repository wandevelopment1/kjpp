<?php

namespace App\Http\Controllers\Admin;

use App\Models\UiConfig;
use Illuminate\Http\Request;
use App\Models\UiConfigGroup;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadManager;
use Illuminate\Support\Facades\Storage;

class UiConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.ui-config.index', ['only' => ['index']]);
        $this->middleware('can:admin.ui-config.show', ['only' => ['show']]);
        $this->middleware('can:admin.ui-config.create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin.ui-config.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin.ui-config.delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $uiConfigs = UiConfig::with('group')->get();

        return view('admin.ui-config.index', compact('uiConfigs'));
    }


    public function create()
    {
        $uiConfigGroups = UiConfigGroup::all();
        return view('admin.ui-config.form', compact('uiConfigGroups'));
    }

    public function edit(UiConfig $uiConfig)
    {
        $uiConfigGroups = UiConfigGroup::all();
        return view('admin.ui-config.form', compact('uiConfig', 'uiConfigGroups'));
    }

    public function edit2(UiConfig $uiConfig)
    {
        $uiConfig->with('group');
        $uiConfigGroups = UiConfigGroup::all();
        return view('admin.ui-config.form-value', compact('uiConfig', 'uiConfigGroups'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'label' => 'required|string',
            'type' => 'required|in:text_field,text_area,ckeditor,image,file',
            'ui_config_group_id' => 'required|exists:ui_config_groups,id',
            'value' => 'nullable',
        ]);

        $uiConfig = new UiConfig();
        $uiConfig->key = $request->key;
        $uiConfig->label = $request->label;
        $uiConfig->type = $request->type;
        $uiConfig->ui_config_group_id = $request->ui_config_group_id;

        // Handle value sesuai type
        if (in_array($request->type, ['image', 'file']) && $request->hasFile('value')) {
            $uiConfig->value = UploadManager::default($request->file('value'), 'ui-config');
        } else {
            $uiConfig->value = $request->value;
        }

        $uiConfig->save();

        return redirect()
            ->route('admin.ui-config.show', $uiConfig->group->slug)
            ->with('success', 'UI Config created successfully');
    }


    public function update(Request $request, UiConfig $uiConfig)
    {
        $request->validate([
            'key' => 'sometimes|required|string',
            'label' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:text_field,text_area,ckeditor,image,file',
            'ui_config_group_id' => 'sometimes|required|exists:ui_config_groups,id',
            'value' => 'nullable',
        ]);

        if ($request->has('key')) {
            $uiConfig->key = $request->key;
        }

        if ($request->has('label')) {
            $uiConfig->label = $request->label;
        }

        if ($request->has('type')) {
            $uiConfig->type = $request->type;
        }

        if ($request->has('ui_config_group_id')) {
            $uiConfig->ui_config_group_id = $request->ui_config_group_id;
        }

        // Handle value sesuai type
        if (in_array($request->type, ['image', 'file']) && $request->hasFile('value')) {
            // Hapus file lama
            if (in_array($uiConfig->type, ['image', 'file']) && $uiConfig->value) {
                UploadManager::defaultDelete($uiConfig->value, 'ui-config');
            }
            $uiConfig->value = UploadManager::default($request->file('value'), 'ui-config');
        } elseif ($request->has('value')) {
            if ($request->value === null) {
                $uiConfig->value = null;
            } elseif (!in_array($request->type, ['image', 'file'])) {
                // Untuk text_field, text_area, ckeditor
                $uiConfig->value = $request->value;
            }
        }

        $uiConfig->save();

        return redirect()->route('admin.ui-config.show', $uiConfig->group->slug)->with('success', 'UI Config updated successfully');
    }


    public function destroy(UiConfig $uiConfig)
    {
        if (in_array($uiConfig->type, ['image', 'file']) && $uiConfig->value) {
            UploadManager::defaultDelete($uiConfig->value, 'ui-config');
        }

        $uiConfigGroup = $uiConfig->group;
        $uiConfig->delete();

        return redirect()
            ->route('admin.ui-config.show', $uiConfigGroup->slug)
            ->with('success', 'UI Config deleted successfully');
    }


    public function show($slug)
    {
        $uiConfigGroup = uiConfigGroup::where('slug', $slug)->first();
        $uiConfigs = UiConfig::where('ui_config_group_id', $uiConfigGroup->id)->get();

        return view('admin.ui-config.show', compact('uiConfigs', 'uiConfigGroup'));
    }

    public function updateValue(Request $request, UiConfig $uiConfig)
    {
        $request->validate([
            'value' => 'nullable',
        ]);

        // Handle value sesuai type saat ini
        if (in_array($uiConfig->type, ['image', 'file']) && $request->hasFile('value')) {
            // Hapus file lama
            if ($uiConfig->value) {
                UploadManager::defaultDelete($uiConfig->value, 'ui-config');
            }
            $uiConfig->value = UploadManager::default($request->file('value'), 'ui-config');
        } elseif ($request->has('value')) {
            if ($request->value === null) {
                $uiConfig->value = null;
            } elseif (!in_array($uiConfig->type, ['image', 'file'])) {
                // Untuk text_field, text_area, ckeditor
                $uiConfig->value = $request->value;
            }
        }

        $uiConfig->save();

        return redirect()->route('admin.ui-config.show', $uiConfig->group->slug)->with('success', 'UI Config value updated successfully');
    }



}
