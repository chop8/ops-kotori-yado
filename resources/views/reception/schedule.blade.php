@extends('layouts.base')
@section('title', $date . ' の予定')
@section('styles')
    <link href="{{ asset('css/reception/reception.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/calendar.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('reception.index') }}">予約表</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reception.index', ['m' => $month]) }}">{{ \Illuminate\Support\Carbon::parse($date)->format('Y年n月') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Carbon::parse($date)->format('d日（D）') }}</li>
            </ol>
        </nav>

        <h4>{{ $date }} の予定</h4>
        <div class="responsive-area">
            <table class="table table-bordered table-schedule">
                <thead>
                <tr>
                    <th class="td-time">時間</th>
                    @foreach ($resources as $name => $class)
                        <th class="{{ $class }}">{{ $name }}</th>
                    @endforeach
                    @if(Cookie::get('is_login'))
                        <th>編集</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($timeSlots as $time)
                    @php
                        $data = $receptions->get($time);
                    @endphp
                    <tr>
                        <th>{{ $time }}</th>
                        @foreach ($resources as $name => $field)
                            <td>
                                @if ($field === 'memo')
                                    {!! nl2br(e($data ? $data->$field : '')) !!}
                                @else
                                    {{ $data ? $data->$field : '' }}
                                @endif
                            </td>
                        @endforeach
                        @if(Cookie::get('is_login'))
                            <td class="td-edit text-center">
                                <button type="button" class="btn btn-sm btn-link p-0" onclick="openEditModal('{{ $time }}')">
                                    <i class="fa-solid fa-pen-to-square h6"></i>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('reception.index', ['m' => $month]) }}" class="btn btn-secondary mt-3">カレンダーに戻る</a>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="time_slot" id="modalTimeSlot">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">編集: <span id="modalDateDisplay"></span> <span id="modalTimeDisplay"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>IN/OUT</label><br>
                            <input type="radio" name="in_out" value="IN" id="in"> <label for="in">IN</label>
                            <input type="radio" name="in_out" value="OUT" id="out"> <label for="out">OUT</label>
                            <input type="radio" name="in_out" value="" id="none"> <label for="none">未選択</label>
                        </div>
                        <div class="mb-3">
                            <label>名前</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>ケージ数</label>
                            <input type="text" name="cage_count" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>利用</label><br>
                            <input type="radio" name="type" value="新規"> 新規
                            <input type="radio" name="type" value="既存"> 既存
                            <input type="radio" name="type" value=""> 未選択
                        </div>
                        <div class="mb-3">
                            <label>区分</label><br>
                            <input type="radio" name="category" value="持込"> 持込
                            <input type="radio" name="category" value="レンタル"> レンタル
                            <input type="radio" name="category" value=""> 未選択
                        </div>
                        <div class="mb-3">
                            <label>お迎え日時</label>
                            <input type="text" name="pickup_at" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>詳細・金額</label>
                            <textarea name="memo" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(time) {
            document.getElementById('modalDateDisplay').innerText = '{{ $date }}';
            document.getElementById('modalTimeDisplay').innerText = time;
            document.getElementById('modalTimeSlot').value = time;

            // Reset form
            document.getElementById('editForm').reset();

            // 呼び出し時に初期化する
            const editModalElement = document.getElementById('editModal');
            const editModal = new bootstrap.Modal(editModalElement);

            // Fetch existing data
            fetch(`{{ route('reception.getData') }}?date={{ $date }}&time_slot=${encodeURIComponent(time)}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const form = document.getElementById('editForm');
                        if (data.in_out) {
                           const radio = form.querySelector(`input[name="in_out"][value="${data.in_out}"]`);
                           if (radio) radio.checked = true;
                        }
                        form.querySelector('input[name="name"]').value = data.name || '';
                        form.querySelector('input[name="cage_count"]').value = data.cage_count || '';
                        if (data.type) {
                           const radio = form.querySelector(`input[name="type"][value="${data.type}"]`);
                           if (radio) radio.checked = true;
                        }
                        if (data.category) {
                           const radio = form.querySelector(`input[name="category"][value="${data.category}"]`);
                           if (radio) radio.checked = true;
                        }
                        form.querySelector('input[name="pickup_at"]').value = data.pickup_at || '';
                        form.querySelector('textarea[name="memo"]').value = data.memo || '';
                    }
                    editModal.show();
                });
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route('reception.save') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('保存に失敗しました');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('通信エラーが発生しました');
            });
        });
    </script>

    <div class="mode-change">
        @if(Cookie::get('is_login'))
            <form name="login" method="post" action="{{ url('/auth/lock') }}?p={{ $date }}" class="form-login">
                @csrf
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <button type="submit" class="btn btn-sm btn-primary">ログアウト</button>
            </form>
        @else
            <form name="login" method="post" action="{{ url('/auth/unlock') }}?p={{ $date }}" class="form-login">
                @csrf
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <input type="password" class="form-control" name="pass" id="pass" placeholder="パスワード" style="width: 100px;">
                <button type="submit" class="btn btn-sm btn-primary">編集</button>
            </form>
        @endif
    </div>
@endsection
