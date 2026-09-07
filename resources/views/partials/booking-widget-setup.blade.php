@php
    use App\Models\ref_tracking;

    if (!($skipRefTracking ?? false)) {
        session()->put('user_ip', request()->ip());

        if (isset($_SERVER['HTTP_REFERER'])) {
            session()->put('ref_url', $_SERVER['HTTP_REFERER']);
        }

        if (request()->get('src') != '') {
            session()->put('bk_src', request()->get('src'));
        }
        if (request()->get('utm_source') == 'PPC') {
            session()->put('bk_src', 'PPC');
        }
        if (request()->get('utm_source') == 'Bing') {
            session()->put('bk_src', 'BING');
        }
        if (request()->get('utm_source') == 'EMAIL') {
            session()->put('bk_src', 'EM');
        }
        if (request()->get('source') == 'webgains') {
            session()->put('bk_src', 'WG');
        }
        if (request()->get('utm_source') == 'Fbads') {
            session()->put('bk_src', 'FB');
        }

        $ip = session()->get('user_ip');
        $data['ref_url'] = session()->get('ref_url');

        if (session()->get('bk_src') != '') {
            $data['traffic_src'] = session()->get('bk_src');
        } else {
            $data['traffic_src'] = 'ORG';
        }

        $data['agentID'] = '1';
        $data['user_ip'] = $ip;
        $data['current_url'] = url()->full();

        if (session()->get('userEmail') != '') {
            $data['email'] = session()->get('userEmail');
        }

        ref_tracking::Create($data);
    }
@endphp
