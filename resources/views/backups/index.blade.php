@extends('sj_layout')
@section('title'){{ __('Database Backups') }}@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-auto">
        <p class="text-center">
            <a href="https://drive.google.com/drive/folders/{{ env('GOOGLE_DRIVE_BACKUPS_FOLDER_ID')}}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <span class="small me-1">{{ __('View all') }}</span>
                <img src="/img/google/drive.png" alt="{{ __('Drive storage') }}" title="{{ __('Drive storage') }}" height="20px">
            </a>
        </p>

        <form action="{{ $route }}" method="POST" class="row mb-3" novalidate>
            @csrf
            {{ method_field($method) }}

                <p class="m-0"><code>CRON</code>: {{ __('Remove backups prior to') }}:</p>
                <div class="input-group input-group-sm mb-3">
                    {{ Form::number('period_count', $periodCount->getValue(), ['class' => 'form-control']) }}
                    {{ Form::select('period', [
                        \App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_MINUTES =>  \App\Services\CSJSettingsService::backupPeriodName(\App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_MINUTES),
                        \App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_HOURS =>  \App\Services\CSJSettingsService::backupPeriodName(\App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_HOURS),
                        \App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_DAYS =>  \App\Services\CSJSettingsService::backupPeriodName(\App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_DAYS),
                        \App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_MONTHS =>  \App\Services\CSJSettingsService::backupPeriodName(\App\Services\CSJSettingsService::BACKUP_RM_PERIOD_VALUE_MONTHS),
                    ], $period->getValue(), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
                    <button class="btn btn-outline-primary" type="submit" id="button-addon2">{{ __('guardar') }}</button>
                </div>
        </form>


        <div class="bg-white border-bottom" style="
          top: calc(var(--header-height) + 1rem);
          position: sticky;
        ">
            <p class="text-muted text-end small my-1">{{ __('Showing :totalItems items', ['totalItems' => count($collection)]) }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-sm text-center align-middle">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Day') }}</th>
                        <th scope="col">{{ __('Expires') }}</th>
                        <th scope="col">{{ __('Backup') }}</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($collection))
                    @php 
                        $header = Carbon\Carbon::parse($collection[0]->getCreated());
                        $hMonth = $header->translatedFormat('F Y'); 
                    @endphp
                    <tr class="text-start text-muted bg-light">
                        <td colspan="3">{{ $hMonth }}</td>
                    </tr>
                    @foreach ($collection as $entity)
                        @php 
                            $current = Carbon\Carbon::parse($entity->getCreated());
                            $cMonth  = $current->translatedFormat('F Y'); 
                        @endphp
                        @if ($cMonth != $hMonth)
                            @php $hMonth = $cMonth @endphp
                            <tr class="text-start text-muted bg-light">
                                <td colspan="3">{{ $cMonth }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td title="{{ $current->translatedFormat('Y-m-d H:i:s') }}">{{ $current->translatedFormat('D d') }}, <small>{{ $current->translatedFormat('H:i') }}</small></td>
                            @php 
                            $expires = Carbon\Carbon::parse(\App\Services\CSJSettingsService::getExpirationDate($entity, $periodCount, $period)) 
                            @endphp
                            <td title="{{ $expires }}">{{ $expires->diffForHumans() }}</td>
                            <td class="text-center">
                               @if ($entity->isUploaded())
                                   <a href="{{ $entity->getFileUrl() }}" target="_blank">
                                       <img src="{{ asset('img/google/drive.png') }}" alt="{{ __('Drive storage') }}" height="16px">
                                   </a>
                               @else
                                   {{ $entity->getStatusName() }}
                               @endif
                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

