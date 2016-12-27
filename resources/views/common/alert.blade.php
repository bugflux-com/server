@if(session()->has('success'))
    <div class="alert -success mtx mbs">{{ session('success') }}</div>
@elseif(session()->has('failure'))
    <div class="alert -error mtx mbs">{{ session('failure') }}</div>
@endif