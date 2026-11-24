@if(session('success'))
    <div class="cs-alert cs-alert-success alert alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close position-static ms-2" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

@if(session('error'))
    <div class="cs-alert cs-alert-danger alert alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close position-static ms-2" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif
