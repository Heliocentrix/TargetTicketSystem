@extends('includes.layout')

@section('sectionTitle')
    Your Services
@stop

@section('content')
@if(session('ticket_success') && session('company_slug'))
@include('tickets.ticketSuccess', ['company_slug' => session('company_slug')]);
@else

<div class="page-heading text-center">
    <h1>Choose a Service</h1>

    @if(auth()->user()->admin)
    <p>Welcome to Support Administration Hub, please choose a service</p>
    @else
    <p>Welcome to your Maintenance &amp; Support Hub, please choose a service</p>
    @endif
</div>

<div class="page-content">
    <div class="row">
        {{-- If there are no ads to display --}}
        @if(count(auth()->user()->Adverts) == 0)
        <div class="col-xs-12">
        @else
        <div class="col-sm-9 col-xs-12">
        @endif
            <div class="btn-section row">
                @if(auth()->user()->admin)
                    {{-- Tickets Section --}}
                    <a href="#" class="btn-section-link btn-maintenance-support" name="btn-maintenance-support">
                        <strong>Maintenance &amp; Support</strong>
                        <p>Click here to upload a request for web development, blog posts, ask a question about your website, download SEO documents or get a quote.</p>
                    </a>
                    <div class="ajaxable" id="maintenance-support-div" @if(request()->segment(1) != 'maintenance') style="display:none;" @endif>
                        @if(request()->segment(1) == 'maintenance')
                        {!! $maintenanceList !!}
                        @endif
                    </div>

                    {{-- Clients Section --}}
                    <a href="#" class="btn-section-link btn-clients" name="btn-clients">
                        <strong>Clients</strong>
                        <p>Create a new client, add new clients and determine who receives emails and how you would like us to respond.</p>
                    </a>
                    <div class="ajaxable" id="clients-div" @if(request()->segment(1) != 'clients') style="display:none;" @endif>
                        @if(request()->segment(1) == 'clients')
                        {!! $clientList !!}
                        @endif
                    </div>

                    {{-- Banners Section --}}
                    <a href="#" class="btn-section-link btn-banners" id="advertDiv">
                        <strong>Adverts</strong>
                        <p>Click here to manage adverts displayed to clients.</p>
                    </a>
                    <div class="clearfix banners-container">
                        <div class="col-md-12 ajaxable" id="banners-div" @if(!isset($advertList)) style="display:none;" @endif>
                            @if(isset($advertList)){!! $advertList !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="banner-table-div" @if(!isset($advertTable)) style="display:none;" @endif>
                            @if(isset($advertTable)){!! $advertTable !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="banner-form-div" @if(!isset($advertForm)) style="display:none;" @endif>
                            @if(isset($advertForm)){!! $advertForm !!}@endif
                        </div>
                    </div>

                    {{-- Services Section --}}
                    <a href="#" class="btn-section-link btn-services" id="servicesDiv">
                        <strong>Services</strong>
                        <p>This icon alows you to add products to your list of links on your clients landing pages - products appear on all client pages.</p>
                    </a>
                    <div class="clearfix services-container">
                        <div class="col-md-12 ajaxable" id="services-div" @if(!isset($serviceList)) style="display:none;" @endif>
                            @if(isset($serviceList)){!! $serviceList !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="services-table-div" @if(!isset($serviceTable)) style="display:none;" @endif>
                            @if(isset($serviceTable)){!! $serviceTable !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="services-form-div" @if(!isset($serviceForm)) style="display:none;" @endif>
                            @if(isset($serviceForm)){!! $serviceForm !!}@endif
                        </div>
                    </div>

                    {{-- SEO Section --}}
                    <a href="#" class="btn-section-link btn-seo-reports-admin">
                        <strong>Upload SEO Reports</strong>
                        <p>This icon allows you to upload SEO Reports to clients accounts.</p>
                    </a>
                    <div class="clearfix services-container">
                        <div class="col-md-12 ajaxable" id="seo-div" @if(!isset($documentList) && request()->segment(2) != 'seo') style="display:none;" @endif>
                            @if(isset($documentList) && request()->segment(2) == 'seo'){!! $documentList !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="seo-table-div" @if(!isset($documentTable) && request()->segment(2) != 'seo') style="display:none;" @endif>
                            @if(isset($documentTable) && request()->segment(2) == 'seo'){!! $documentTable !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="seo-form-div" @if(!isset($documentForm) && request()->segment(2) != 'seo') style="display:none;" @endif>
                            @if(isset($documentForm) && request()->segment(2) == 'seo'){!! $documentForm !!}@endif
                        </div>
                    </div>

                    {{-- Information Section --}}
                    <a href="#" class="btn-section-link btn-information-documents-admin">
                        <strong>Upload Information Documents</strong>
                        <p>This icon allows you to upload information documents for clients to refer to.</p>
                    </a>
                    <div class="clearfix services-container">
                        <div class="col-md-12 ajaxable" id="info-div" @if(!isset($documentList) && request()->segment(2) != 'info') style="display:none;" @endif>
                            @if(isset($documentList) && request()->segment(2) == 'info'){!! $documentList !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="info-table-div" @if(!isset($infoDocumentTable) && request()->segment(2) != 'info') style="display:none;" @endif>
                            @if(isset($documentTable) && request()->segment(2) == 'info'){!! $documentTable !!}@endif
                        </div>
                        <div class="col-md-12 ajaxable" id="info-form-div" @if(!isset($infoDocumentForm) && request()->segment(2) != 'info') style="display:none;" @endif>
                            @if(isset($documentForm) && request()->segment(2) == 'info'){!! $documentForm !!}@endif
                        </div>
                    </div>
                    
                    {{-- Report Ticket Section --}}
                    <a href="#" class="btn-section-link btn-report-ticket-admin">
                        <strong>Ticket Cost Reports</strong>
                        <p>This icon allows you to  generate report about ticket that have cost updated.</p>
                    </a>
                    <div class="clearfix services-container">
                        <div class="col-md-12 ajaxable" id="report-ticket" @if(!isset($ticketlog)) style="display:none;" @endif>
                           @if(isset($ticketlog)){!! $ticketlog !!}@endif
                        </div>
                    </div>
                @else
                    <a href="{{ url(auth()->user()->company_slug . '/tickets') }}" class="btn-section-link btn-maintenance-support" id="btn-maintenance-support">
                        <strong>Maintenance &amp; Support</strong>
                        <p>Click here to upload a request for web development, blog posts, ask a question about your website, download SEO documents or get a quote.</p>
                    </a>

                    <a href="{{ url(auth()->user()->company_slug . '/documents/seo') }}" class="btn-section-link btn-seo-reports" id="btn-seo-reports">
                        <strong>SEO Documents</strong>
                        <p>Click here to view your current &amp; previous SEO Docs.</p>
                    </a>

                    <a href="{{ url(auth()->user()->company_slug . '/documents/info') }}" class="btn-section-link btn-information-documents" id="btn-information-documents">
                        <strong>Information Documents</strong>
                        <p>Click here to view your documents. Information, instructions and Terms &amp; Conditions.</p>
                    </a>

                    @if(count(auth()->user()->services))
                        @foreach(auth()->user()->services as $service)
                            <a href="{{ $service->link }}" target="#blank" class="btn-section-link btn-section-{{ $service->id }}">
                                <strong>{{ $service->heading }}</strong>
                                <p>{!! nl2br($service->text) !!}</p>
                            </a>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
        @if(!auth()->user()->admin)
        <div class="col-sm-3 hidden-xs text-center">
            @include('includes.banners')
        </div>
        @endif
    </div>
</div>
@endif
@endsection

@section('styles')
    @if(count(auth()->user()->services))
        <style type="text/css">
            @foreach(auth()->user()->services as $service)
                .btn-section-{{ $service->id }}:before {
                    background:none;
                    background-image: url('{{ url('img/' . $service->icon) }}?w=146&amp;h=146&amp;fit=max') !important;
                    background-position-y: top;
                    background-repeat: no-repeat;
                }
                .btn-section-{{ $service->id }}:hover:before {
                    background-image: url('{{ url('img/' . $service->icon_rollover) }}?w=146&amp;h=146&amp;fit=max') !important;
                    background-position-y: top;
                }
            @endforeach
        </style>
    @endif
@stop
