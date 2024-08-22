<!-- Notifications -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="notifications">
	<div class="offcanvas-header py-0">
		<h5 class="offcanvas-title py-3">Activity</h5>
		<button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
			<i class="ph-x"></i>
		</button>
	</div>

	<div class="offcanvas-body p-0">
		<div class="bg-light fw-medium py-2 px-3">New notifications</div>
		<div class="p-3">
		    @if(Auth::user())
			@foreach(Auth::user()->Notifications as $notification)
			@if($notification->read_at == null)
			<div class="d-flex align-items-start mb-3">
				<a href="#" class="status-indicator-container me-3">
					<img src="{{URL::asset('assets/images/demo/users/face1.jpg')}}" class="w-40px h-40px rounded-pill" alt="">
					<span class="status-indicator bg-success"></span>
				</a>
				<div class="flex-fill">
					<a href="/readNotification/{{ $notification->id}}" class="fw-semibold">{{ $notification->data['data']}}</a></a>
				</div>
			</div>
			@endif
			@endforeach
			@endif
		</div>
	</div>
</div>
<!-- /notifications -->