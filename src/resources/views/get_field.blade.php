<div class="row">
    <p>{{ $field->getTitleField()  }}</p>
</div>
<div class="row">
    <input type="text" name="fields[NEW][name]" class="form-control" required placeholder="Имя поля">
</div>
<div class="row">
    <input type="text" name="fields[NEW][title]" class="form-control" required placeholder="Название поля">
</div>
<div class="row">
    <input type="hidden" name="fields[NEW][class]" class="form-control" value="{{ $field::class }}">
</div>

<div class="row">
    <input type="text" name="fields[NEW][default]" class="form-control" @if(isset($defaults_value['default'])) value="{{$defaults_value['default']}}" @else  value="" @endif placeholder="Значение по умолчанию">
</div>
<div class="row">
    показывать в списке <input type="checkbox" name="fields[NEW][list]"  value="1">
</div>
<div class="row">
    nullable <input type="checkbox" name="fields[NEW][nullable]"   value="1">
</div>
<div class="row">
    уникальное <input type="checkbox" name="fields[NEW][unique]"  value="1">
</div>
<div class="row">
    <input type="number" name="fields[NEW][length]" class="form-control" @if(isset($defaults_value['length'])) value="{{$defaults_value['length']}}" @else  value="" @endif placeholder="длина">
</div>
@if(count($config)>0)
    @foreach($config as $key=>$type)
        <div class="row">
            <input type="string" name="fields[NEW][{{ $key }}]" class="form-control" value=""  placeholder="{{ $key }}">
        </div>
    @endforeach
@endif

