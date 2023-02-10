<form action="{{ \Illuminate\Support\Facades\Route::currentRouteName() }}" method="GET" id="search-form">
    @csrf
    <div class="d-flex">
        <input type="search" name="q" class="form-control form-control-sm" style="border-top-right-radius: 0;border-bottom-right-radius: 0;" value="{{ old('q') }}">
        <div>
            <span class="input-group-text" style="border-bottom-left-radius: 0;border-top-left-radius: 0;" onclick="document.getElementById('search-form').submit()"><i class="fas fa-search fa-fw" style="height: 1.05em"></i></span>
        </div>
    </div>
</form>
