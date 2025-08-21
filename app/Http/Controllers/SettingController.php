<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Only managers can access settings
        if (!Auth::user()->isManager()) {
            abort(403);
        }
        
        // Group settings by category
        $companySettings = Setting::where('group', 'company')->get();
        $invoiceSettings = Setting::where('group', 'invoice')->get();
        $paymentSettings = Setting::where('group', 'payment')->get();
        $notificationSettings = Setting::where('group', 'notification')->get();
        $systemSettings = Setting::where('group', 'system')->get();
        
        return view('settings.index', compact(
            'companySettings',
            'invoiceSettings',
            'paymentSettings',
            'notificationSettings',
            'systemSettings'
        ));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        // Only managers can update settings
        if (!Auth::user()->isManager()) {
            abort(403);
        }
        
        // Validate settings based on their type
        $validationRules = [];
        foreach ($request->except('_token') as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                switch ($setting->type) {
                    case 'number':
                    case 'integer':
                        $validationRules[$key] = 'numeric';
                        break;
                    case 'boolean':
                        $validationRules[$key] = 'boolean';
                        break;
                    case 'json':
                    case 'array':
                        $validationRules[$key] = 'json';
                        break;
                    default:
                        $validationRules[$key] = 'string';
                }
            }
        }
        
        $request->validate($validationRules);
        
        // Update each setting
        foreach ($request->except('_token') as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Handle boolean values
                if ($setting->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                
                // Update the setting
                $setting->value = $value;
                $setting->save();
            }
        }
        
        return redirect()->route('manager.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
