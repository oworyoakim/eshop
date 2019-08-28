<!-- To the right -->
<div class="pull-right hidden-xs">
    Powered by KimDev
</div>
<!-- Default to the left -->
<strong>Copyright©{{date('Y')}} <a href="http://{{ \App\Models\Setting::where('setting_key','company_website')->first()->setting_value }}" target="_blank">{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</a>.</strong> All rights reserved.

