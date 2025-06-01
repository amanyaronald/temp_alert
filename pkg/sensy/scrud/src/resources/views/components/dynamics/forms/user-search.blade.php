@props([
    'action' => '_searchUsers',
    'result' => 'user_result',
    // 'searchType' => 'fn', // fn = full name, em = email, ph = phone number
    'query',
    'searchCol' => 6,
    'selectedId' => 'user_id',
    'customSearch' => false,
    'noResult' => false,
])
<div class="py-2">

    @if ($customSearch == false)
        <div class="d-flex justify-content-center">
            <x-scrud::dynamics.forms.input col="{{ $searchCol }}" model='{{ $query }}' type='text'
                                           placeholder="Enter Email/Phone Number" label='Email/Phone Number'
                                           :hasLabel="false">
                @slot('append')
                    <x-scrud::dynamics.button action="_searchUsers('{{ $result }}','{{ $query }}' )" text="Search"
                                              :hasIcon="true" icon="bx bx-search" :hasLoader="true"
                                              customProcessmessage="Searching..."
                                              :isOutlined="true"/>
                @endslot
            </x-scrud::dynamics.forms.input>
        </div>
    @endif
    <div class="d-flex justify-content-center">
        @error($query)
        <div class="text-danger">{{ $message }}</div>
        @enderror
        @error($selectedId)
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    @if (!$noResult)
        @if ($this->$result)
            <x-scrud::dynamics.table :paginate="false">
                <x-slot name="thead">
                    <th>Email</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </x-slot>
                <x-slot name="tbody">
                    @foreach ($this->$result as $data)
                        <tr>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->username }}</td>
                            <td>{{ $data->firstname }}</td>
                            <td>{{ $data->lastname }}</td>
                            <td>{{ $data->phone_number }}</td>
                            <td class="py-1">
                                <button wire:click="$set('{{ $selectedId }}',{{ $data->id }})"
                                        class="btn btn-{{ $this->$selectedId == $data->id ? 'primary' : 'outline-primary' }} btn-sm">
                                    <i class="fa fa-check"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-scrud::dynamics.table>
        @endif
    @endif
</div>
