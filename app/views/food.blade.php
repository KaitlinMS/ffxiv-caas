@extends('layout')

@section('content')

<h1>Food</h1>

<p>
	Food provides a certain percentage of your stat up to the maximum.  The Threshold is what it takes to reach that percentage and maximum.  
	For example, if you had 65 Craftsmanship, it would be a waist to use <em>Mashed Popotoes</em> when <em>Mint Lassi</em> is available (and assumedly cheaper).
</p>

@foreach(array('DOH' => 1, 'DOL' => 2) as $job => $stat_limit)
<?php $job = Job::where('abbreviation', $job)->first(); ?>

<h3>{{ $job->name }}</h3>

<table class='table table-bordered'>
	<tbody>
		<?php $last = null; ?>
		@foreach(Food::with('stats')->where('job_id', $job->id)->get() as $food)
			<?php $concat = $food->stats[0]->name . (@$food->stats[1]->name ?: ''); ?>
			@if ($concat != $last)
				<?php $last = $concat; ?>
				<tr>
					<th> </th>
					@foreach(range(0, $stat_limit - 1) as $place)
					<td colspan='{{ 6 / $stat_limit }}' class='text-center'>
						@if (isset($food->stats[$place]))
						<div>
							<img src='/img/stats/{{ $food->stats[$place]->name }}.png' class='stat-icon'>
							{{ $food->stats[$place]->name }} Bonus
						</div>
						@endif
					</td>
					@endforeach
				</tr>
				<tr>
					<th> </th>
					@foreach(range(1, $stat_limit) as $ignore)
					<th class='text-center'>
						Percent
					</th>
					<th class='text-center'>
						Maximum
					</th>
					<th class='text-center'>
						Threshold
					</th>
					@endforeach
				</tr>
			@endif
			<tr>
				<th>{{ $food->name }}</th>
					@foreach(range(0, $stat_limit - 1) as $place)
					@if( ! isset($food->stats[$place]))
					<td></td><td></td><td></td>
					@else
					<td class='text-center'>
						{{ $food->stats[$place]->pivot->percent }}%
					</td>
					<td class='text-center'>
						{{ $food->stats[$place]->pivot->maximum }}
					</td>
					<td class='text-center'>
						{{ round($food->stats[$place]->pivot->maximum / ($food->stats[$place]->pivot->percent / 100)) }}
					</td>
					@endif
					@endforeach
			</tr>

			<?php /*var_dump($food->stats[0]->name); ?>
			<?php var_dump($food->stats[0]->pivot->percent); ?>
			<?php var_dump(@$food->stats[1]->name ?: ''); ?>
			<?php var_dump(@$food->stats[1]->pivot->percent);*/ ?>
		@endforeach
	</tbody>
</table>
@endforeach
	
@stop