
@php
    $site_settings_main=[];
        $settingsAll = App\Models\settings::all();
                foreach ($settingsAll as $setting) {
                    $site_settings_main[$setting->field_name] = $setting->field_value;
                }
                $site_settings_main = normalize_site_settings($site_settings_main);

@endphp


@include('layouts.nav')