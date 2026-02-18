@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Profile Update Requests') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Requests') }}</h5>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Seller') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th width="25%">{{ translate('Options') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $key => $req)
                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>
                                <strong>{{ $req->user->name }}</strong>
                            </td>

                            <td>
                                @if ($req->status == 'pending')
                                    <span class="badge badge-inline badge-warning">Pending</span>
                                @elseif($req->status == 'approved')
                                    <span class="badge badge-inline badge-success">Approved</span>
                                @else
                                    <span class="badge badge-inline badge-danger">Rejected</span>
                                @endif
                            </td>

                            <td>

                                <button class="btn btn-info btn-sm" onclick="viewRequest({{ $req->id }})">
                                    <i class="las la-eye"></i> View
                                </button>

                                {{-- @if ($req->status == 'pending')
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmApprove({{ $req->id }})">
                                        <i class="las la-check"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmReject({{ $req->id }})">
                                        <i class="las la-times"></i>
                                    </button>
                                @endif --}}
                                @if ($req->status == 'pending')
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmApprove({{ $req->id }})">
                                        <i class="las la-check"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmReject({{ $req->id }})">
                                        <i class="las la-times"></i>
                                    </button>
                                @else
                                    {{-- DELETE ONLY IF APPROVED OR REJECTED --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmDelete({{ $req->id }})">
                                        <i class="las la-trash"></i>
                                    </button>
                                @endif


                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                No Requests Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="aiz-pagination mt-3">
                {{ $requests->links() }}
            </div>

        </div>
    </div>

    {{-- VIEW MODAL --}}
    <div class="modal fade" id="viewRequestModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="requestDetailsBody"></div>

            </div>
        </div>
    </div>

    {{-- CONFIRM MODAL --}}
    <div class="modal fade" id="confirmActionModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTitle">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="confirmMessage"></p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmSubmitBtn">Yes Continue</button>
                </div>

            </div>
        </div>
    </div>

    {{-- HIDDEN FORMS --}}
    <form id="approveForm" method="POST">@csrf</form>
    <form id="rejectForm" method="POST">@csrf</form>
    <form id="deleteForm" method="POST">@csrf @method('DELETE') </form>
@endsection

@section('script')
    <script>
        function viewRequest(id) {

            $.get("{{ route('admin.profile.requests.details', ':id') }}"
                .replace(':id', id),
                function(data) {

                    let html = '';
                    let req = data.request;

                    for (let key in req.requested_data) {

                        let value = req.requested_data[key];

                        if (key == 'avatar_original') {
                            html += `
<p><b>Image :</b><br>
<img src="/uploads/all/${value}" width="150">
</p>`;
                        } else if (key == 'password') {
                            html += `<p><b>Password :</b> ****** (Change Requested)</p>`;
                        } else {
                            html += `<p><b>${key} :</b> ${value}</p>`;
                        }

                    }

                    $('#requestDetailsBody').html(html);
                    $('#viewRequestModal').modal('show');

                });
        }


        function confirmApprove(id) {

            let url = "{{ route('admin.profile.requests.approve', ':id') }}"
                .replace(':id', id);

            $('#approveForm').attr('action', url);

            $('#confirmTitle').text("Approve Request");
            $('#confirmMessage').text("Are you sure you want to approve this request?");

            $('#confirmSubmitBtn').off('click').on('click', function() {
                $('#approveForm').submit();
            });

            $('#confirmActionModal').modal('show');
        }


        function confirmReject(id) {

            let url = "{{ route('admin.profile.requests.reject', ':id') }}"
                .replace(':id', id);

            $('#rejectForm').attr('action', url);

            $('#confirmTitle').text("Reject Request");
            $('#confirmMessage').text("Are you sure you want to reject this request?");

            $('#confirmSubmitBtn').off('click').on('click', function() {
                $('#rejectForm').submit();
            });

            $('#confirmActionModal').modal('show');
        }

        function confirmDelete(id) {

            let url = "{{ route('admin.profile.requests.delete', ':id') }}"
                .replace(':id', id);

            $('#deleteForm').attr('action', url);

            $('#confirmTitle').text("Delete Request");
            $('#confirmMessage').text("Are you sure you want to delete this record?");

            $('#confirmSubmitBtn').off('click').on('click', function() {
                $('#deleteForm').submit();
            });

            $('#confirmActionModal').modal('show');
        }
    </script>
@endsection
