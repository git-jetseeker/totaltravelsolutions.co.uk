
@php
    $site_settings_main=[];
        $settingsAll = App\Models\settings::all();
                foreach ($settingsAll as $setting) {
                    $site_settings_main[$setting->field_name] = $setting->field_value;
                }
@endphp


@include('layouts.nav')