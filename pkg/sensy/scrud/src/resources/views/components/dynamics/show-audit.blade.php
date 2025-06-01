@props(['audits'])
{{-- @dd($audits) --}}
<div class="row scrollable-container" style="max-height: 230px; overflow: auto;">
    <x-scrud::dynamics.table :paginate="false">
        <x-slot name="thead">
            <th>No.</th>
            <th>Role</th>
            <th>Name</th>
            <th>Action</th>
            <th>Verdict</th>
            <th>Comment</th>
            <th>Date</th>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($audits as $index => $audit)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $audit->role->name }}</td>
                    <td>{{ $audit->user->full_name }}</td>
                    <td>{{ $audit->workflow_action }}</td>
                    <td>{{ $audit->action }}</td>
                    <td class="text-wrap" style="max-width: 200px">{{ $audit->comments }}</td>
                    <td>{{ $audit->action_date }}</td>
                </tr>
            @endforeach
        </x-slot>
    </x-scrud::dynamics.table>
</div>
