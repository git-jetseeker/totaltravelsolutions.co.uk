@if ($company->aph_id == '')
    @if ($company->logo == '')
        @if ($company->parking_type == 'Park and Ride')
            @php $logo = 1; @endphp
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ asset('storage/app/companies/Park_Ride.jpg') }}" alt="{{ $company->name }}">
        @else
            @php $logo = 2; @endphp
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ asset('storage/app/companies/Meet_Greet_Logo.jpg') }}" alt="{{ $company->name }}">
        @endif
    @else
        @php $contains = Str::contains($company->logo, 'companies'); @endphp
        @if (isset($company->park_api) && $company->park_api == 'holiday' && $contains != true)
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ $company->logo }}" alt="{{ $company->name }}">
        @elseif (isset($company->park_api) && $company->park_api == 'a2z' && $contains != true)
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ $company->logo }}" alt="{{ $company->name }}">
        @elseif (isset($company->park_api) && $company->park_api == 'Opitech')
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ $company->logo }}" alt="{{ $company->name }}">
        @else
            <img class="js-apb-deal-card__logo-img" loading="lazy" src="{{ 'https://www.dashboard.jetseekergroup.com/' . str_replace('public/', 'storage/', $company->logo) }}" alt="{{ $company->name }}">
        @endif
    @endif
@else
    @if ($company->logo == '')
        @if ($company->parking_type == 'Park and Ride')
            @php $logo = 1; @endphp
        @else
            @php $logo = 2; @endphp
        @endif
    @else
        <img class="js-apb-deal-card__logo-img" loading="lazy" src="https:{{ $company->logo }}" alt="{{ $company->name }}">
    @endif
@endif
