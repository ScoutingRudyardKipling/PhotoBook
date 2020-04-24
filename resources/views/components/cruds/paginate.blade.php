<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{ $var->appends(request()->query())->links() }}
    </div>
</div>