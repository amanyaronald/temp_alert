 @props(['data', 'selected' => [], 'attribute' => 'id'])
 <div class="form-check ">
     <input class="form-check-input" type="checkbox" id="menuCheck{{ $data[$attribute] }}" @checked(in_array($data[$attribute], $selected))
         wire:click="selectItem('{{ $data[$attribute] }}')">
     <label class="form-check-label" for="menuCheck{{ $data[$attribute] }}"></label>
 </div>
