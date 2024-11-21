@include('clients.blocks.header')
<div class="user-profile">
    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Ảnh đại diện</div>
                    <div class="card-body text-center">
                        <img id="avatarPreview" class="img-account-profile rounded-circle mb-2"
                            src="{{ asset('admin/assets/images/user-profile/' . $user->avatar) }}"
                            style="width:160px; height: 160px;" alt="Ảnh đại diện {{ $user->avatar }}">

                        <div class="small font-italic text-muted mb-4">JPG hoặc PNG không lớn hơn 5 MB</div>
                        <input type="file" name="avatar" id="avatar" style="display: none" accept="image/*">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" class="__token">
                        <input type="hidden" name="" value="{{ route('change-avatar') }}" class="label_avatar">
                        <label for="avatar" class="btn btn-primary">Tải ảnh lên</label>
                    </div>
                </div>

                <div class="card mb-4 mb-xl-0">
                    <button class="btn btn-primary" id="update_password_profile">Đổi mật khẩu </button>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Thông tin tài khoản</div>
                    <div class="card-body">
                        <form action="{{ route('update-user-profile') }}" method="POST" name="updateUser"
                            class="updateUser">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="inputFullName">Họ và tên</label>
                                    <input class="form-control" id="inputFullName" type="text"
                                        placeholder="Họ và tên" value="{{ $user->fullName }}" required>
                                </div>
                            </div>
                            @csrf
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="inputLocation">Địa chỉ</label>
                                    <input class="form-control" id="inputLocation" type="text" placeholder="Địa chỉ"
                                        value="{{ $user->address }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                <input class="form-control" id="inputEmailAddress" type="email" placeholder="Email"
                                    value="{{ $user->email }}" required>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" id="inputPhone" type="number"
                                        placeholder="Số điện thoại" value="{{ $user->phoneNumber }}" required>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit" id="update_profile">Lưu thông tin</button>
                        </form>
                    </div>
                </div>
                <div class="card mb-4 ">
                    <div class="card-body" id="card_change_password">
                        <div class="invalid-feedback" style="margin-top:-15px" id="validate_password"></div>
                        <form action="{{ route('change-password') }}" method="post" class="change_password_profile">
                            @csrf
                            <div class="row gx-3">
                                <div class="col-md-4">
                                    <input class="form-control" id="inputOldPass" type="text"
                                        placeholder="Nhập mật khẩu cũ" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" id="inputNewPass" type="text"
                                        placeholder="Nhập mật khẩu mới" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" type="submit">Thay đổi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('clients.blocks.footer')
