@extends('invoice::admin.layout')

@section('title', 'Set role and permitions')

@section('icon')
    <i class="mdi mdi-book-account module-icon-svg-fill"></i>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <script>
        $(document).ready(function () {
            $('body').on('click', '.js-check-for-checked input[type="checkbox"]', function () {
                checkForCheckedBoxes();
            });

            checkForCheckedBoxes();
        });

        function checkForCheckedBoxes() {
            $('.js-check-for-checked').each(function (index) {
                var checked = false;
                $(this).find('input[type="checkbox"]').each(function (index) {
                    if ($(this).is(':checked')) {
                        checked = true;
                    }
                });

                if (checked == true) {
                    $(this).find('td').addClass('bg-primary-opacity-1');
                } else {
                    $(this).find('td').removeClass('bg-primary-opacity-1');
                }
            });
        }

        function checkAllFromThisGroup(groupID, groupType, state) {
            var groupID = '#' + groupID;

            if (state) {
                $('.js-all-' + groupType, groupID).prop("checked", true);
            } else {
                $('.js-all-' + groupType, groupID).prop("checked", false);
            }
        }

        function checkEverythingFromThisGroup(groupID, state) {
            checkAllFromThisGroup(groupID, 'view', state);
            checkAllFromThisGroup(groupID, 'create', state);
            checkAllFromThisGroup(groupID, 'edit', state);
            checkAllFromThisGroup(groupID, 'delete', state);

            checkForCheckedBoxes();
        }
    </script>


    @if(isset($role) && $role)
        <form id="form_validation" method="post" action="{{ route('roles.update', $role->id) }}">
            @method('PUT')
            @else
                <form method="post" action="{{ route('roles.store') }}">
                    @endif
                    @csrf

                    <div class="form-group mx-auto" style="max-width: 385px">
                        <label class="control-label">Role Name</label>
                        <small class="text-muted d-block mb-2">What is the name of the role?</small>
                        <input type="text" class="form-control" name="name" value="@if(isset($role)){{$role->name}}@else{{old('name')}}@endif" required>

                        @if ($errors->has('name'))
                            <label id="name-error" class="error d-block" for="email">{{ $errors->first('name') }}</label>
                        @endif
                    </div>

                    @foreach($permissionGroups as $permissionGroupName=>$permissionGroup)
                        @php
                            $permissionGroupHash = md5($permissionGroupName)
                        @endphp

                        <div class="mb-4 mt-4">
                            <div class="row d-flex justify-content-end align-items-end">
                                <div class="col-md-8">
                                    <div class="px-3">
                                        <h5 class="font-weight-bold" style="text-transform: capitalize;">{{$permissionGroupName}}</h5>
                                        <small class="text-muted">
                                            The user can operate with the content of the website like edit pages, categories, posts, tags.
                                            Please check below what are the avaliable operations that user can do.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="button" class="btn btn-link btn-sm" onclick="checkEverythingFromThisGroup('{{$permissionGroupHash}}', true)">Select All</button>
                                    <button type="button" class="btn btn-link btn-sm" onclick="checkEverythingFromThisGroup('{{$permissionGroupHash}}', false)">Unselect All</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="bg-white p-3">
                                        <div class="table-responsive">
                                            <table class="table table-permissions" id="{{$permissionGroupHash}}">
                                                <thead>
                                                <tr>
                                                    <th scope="col" colspan="2">
                                                        <h6 class="font-weight-bold mb-0"><i class="mdi mdi-text mdi-18px mr-2 text-primary"></i> Add and edit {{$permissionGroupName}}</h6>
                                                    </th>
                                                    <th class="text-center font-weight-normal" scope="col">View</th>
                                                    <th class="text-center font-weight-normal" scope="col">Create</th>
                                                    <th class="text-center font-weight-normal" scope="col">Edit</th>
                                                    <th class="text-center font-weight-normal" scope="col">Delete</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="no-hover">
                                                    <th scope="row" rowspan="100" class="row-desc">
                                                        <small class="text-muted d-block">Click on the checkbox to allow the users can {{strtolower($permissionGroupName)}} actions?</small>
                                                        <a href="#" class="btn btn-link px-0">Check tutorial how to set a role</a>
                                                    </th>

                                                    <td class="row-module-name">
                                                        <small class="text-muted">Select all from the column</small>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="check_all_from_view_{{$permissionGroupHash}}" onclick="checkAllFromThisGroup('{{$permissionGroupHash}}', 'view', $(this).is(':checked'))">
                                                            <label class="custom-control-label" for="check_all_from_view_{{$permissionGroupHash}}"></label>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input js-check-all-from-create" id="check_all_from_create_{{$permissionGroupHash}}" onclick="checkAllFromThisGroup('{{$permissionGroupHash}}', 'create', $(this).is(':checked'))">
                                                            <label class="custom-control-label" for="check_all_from_create_{{$permissionGroupHash}}"></label>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input js-check-all-from-edit" id="check_all_from_edit_{{$permissionGroupHash}}" onclick="checkAllFromThisGroup('{{$permissionGroupHash}}', 'edit', $(this).is(':checked'))">
                                                            <label class="custom-control-label" for="check_all_from_edit_{{$permissionGroupHash}}"></label>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input js-check-all-from-delete" id="check_all_from_delete_{{$permissionGroupHash}}" onclick="checkAllFromThisGroup('{{$permissionGroupHash}}', 'delete', $(this).is(':checked'))">
                                                            <label class="custom-control-label" for="check_all_from_delete_{{$permissionGroupHash}}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @foreach ($permissionGroup as $key=>$permission)
                                                    @php
                                                        $permissionHash = md5($permission['name'])
                                                    @endphp
                                                    <tr class="js-check-for-checked">
                                                        <td class="row-module-name">
                                                            <img src="{{$permission['icon']}}" class="module-img"/>
                                                            {{$permission['name']}}
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="permission[]" value="module.{{strtolower($permission['name'])}}.view" @if(in_array('module.'.strtolower($permission['name']).'.view', $selectedPermissions))checked="checked" @endif class="custom-control-input js-all-view" id="customCheck1_{{$permissionHash}}">
                                                                <label class="custom-control-label" for="customCheck1_{{$permissionHash}}"></label>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="permission[]" value="module.{{strtolower($permission['name'])}}.create" @if(in_array('module.'.strtolower($permission['name']).'.create', $selectedPermissions))checked="checked" @endif class="custom-control-input js-all-create" id="customCheck2_{{$permissionHash}}">
                                                                <label class="custom-control-label" for="customCheck2_{{$permissionHash}}"></label>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="permission[]" value="module.{{strtolower($permission['name'])}}.edit" @if(in_array('module.'.strtolower($permission['name']).'.edit', $selectedPermissions))checked="checked" @endif class="custom-control-input js-all-edit" id="customCheck3_{{$permissionHash}}">
                                                                <label class="custom-control-label" for="customCheck3_{{$permissionHash}}"></label>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="permission[]" value="module.{{strtolower($permission['name'])}}.delete" @if(in_array('module.'.strtolower($permission['name']).'.delete', $selectedPermissions))checked="checked" @endif class="custom-control-input js-all-delete" id="customCheck4_{{$permissionHash}}">
                                                                <label class="custom-control-label" for="customCheck4_{{$permissionHash}}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-outline-danger" type="reset"><i class="mdi mdi-cancel"></i> Cancel</button>
                    <button class="btn btn-outline-success float-right waves-effect" type="submit"><i class="mdi mdi-content-save"></i> Save</button>
                </form>
@endsection