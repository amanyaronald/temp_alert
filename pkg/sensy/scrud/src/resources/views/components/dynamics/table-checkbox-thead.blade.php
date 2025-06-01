 @props([
     'width' => '20px',
     'align' => 'middle',
     'id' => 'transactionCheck01',
 ])
 <th style="width: {{ $width }};">
     <div class="form-check font-size-16 align-{{ $align }}">
         <input class="form-check-input" type="checkbox" id="{{ $id }}">
         <label class="form-check-label" for="{{ $id }}"></label>
     </div>
 </th>
