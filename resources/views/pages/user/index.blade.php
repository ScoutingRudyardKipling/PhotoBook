@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">{{__('app.action.View')}} {{__('app.users')}}</h1>
                </div>
                <div class="card-body">
                    <div class="justify-content-end d-flex">@include('components.cruds.block-add-option', ['href'=>route('user.create')])</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>{{__('app.User')}}{{__('app.name')}}</th>
                                <th>{{__('app.Role')}}</th>
                                <th class="text-center" style="width: 100px;">{{__('app.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->roles()->first()->name }}</td>

                                    <td class="text-center">
                                        <div class="btn-group" style="margin:0;">
                                            @include('components.cruds.table-action-show', ['href' => route('user.show', $user->id)])
                                            @include('components.cruds.table-action-edit', ['href' => route('user.edit', $user->id)])
                                            @include('components.cruds.table-action-delete', [
                                                'item' => $user,
                                                'route' => route('user.destroy', $user->id),
                                                'modalTitle' => __('app.User') . ' ' . __('app.action.deleting'),
                                                'modalBody' => __('validation.are-you-sure', ['Attribute' => __('app.user'), 'value' => $user->name])
                                            ])
                                        </div>
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row pt-10">
                        <div class="col-12">
                            @include('components.cruds.paginate', ['var' => $users])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
