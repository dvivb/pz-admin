@if($errors->has($errorKey))
    <script>
        $('#importer-modal').modal('toggle');
    </script>
@endif

<div class="input-group {!! !$errors->has($errorKey) ?: 'has-error' !!} input-group-sm">

    <span class="input-group-addon"><strong>{{$label}}</strong></span>

    @include('admin::form.error')
    <input type="file" class="{{$class}}" name="{{$name}}" {!! $attributes !!} />
    @include('admin::form.help-block')
</div>
