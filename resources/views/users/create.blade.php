@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Create a new user
				</div>

				<div class="panel-body">
					{{Form::open(['url' => route('user_store')])}}
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" class="form-control" id="name" aria-describedby="name" name="name" required>	    
					</div>		
										
					{{Form::bsselect('User Role', 'role_id', $roles->pluck('display_name','id'),'')}}			

					<div class="form-group">
						<label for="email">Email</label>
						<input type="text" class="form-control" id="email" aria-describedby="email" name="email" required>	    						
					</div>		

					<div class="form-group">
						<label for="email-confirm">Email Confirmation</label>
						<input type="text" class="form-control" id="email-confirm" aria-describedby="email-confirm" name="email_confirmation" required>	    
					</div>		
					
					<hr>	
					
            		{{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
					<a href="/users" class="btn btn-danger pull-right">Cancel</a>
					@if(count($errors)>0)
						<div class="alert alert-danger">
							<ul>
								@foreach($errors->all() as $err)
									<li>{{$err}}</li>
								@endforeach
							</ul>
						</div>
					@endif
					{{Form::close()}}		

					
				</div>
			</div>
		</div>
	</div>

	@push('scripts')
		 <script type="text/javascript">
			 $(function () {
                $('#active_from').datetimepicker();
                $('#active_till').datetimepicker();
            });       
        </script>
	@endpush
@endsection