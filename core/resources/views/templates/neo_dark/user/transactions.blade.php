@extends($activeTemplate.'layouts.master')

@section('content')

    @include($activeTemplate.'partials.user-breadcrumb')
    <section class="feature-section pt-150 pb-150">
        <div class="container">
            <div class="row justify-content-center mt-2">

                <div class="col-md-12">
                    <div class="text-md-right text-center mb-5">
                        <a href="
                        @if(request()->routeIs('user.transactions.deposit'))
                        javascript:void(0)
                        @else
                        {{ route('user.transactions.deposit') }}
                        @endif" 
                        class="btn btn-primary mb-3 mr-3
                        @if(request()->routeIs('user.transactions.deposit'))
                        btn-active
                        @endif
                        ">@lang('Deposit Wallet')</a>
                        <a href="
                        @if(request()->routeIs('user.transactions.interest'))
                        javascript:void(0)
                        @else
                        {{ route('user.transactions.interest') }}
                        @endif" 
                        class="btn btn-primary mb-3
                        @if(request()->routeIs('user.transactions.interest'))
                        btn-active
                        @endif
                        ">@lang('Interest Wallet')</a>
                    </div>
                </div>


                <div class="col-md-12">


                    <div class="table-responsive--sm neu--table">

                        <table class="table table-striped text-white">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Trx')</th>
                                <th scope="col">@lang('Details')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Remaining balance')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $k=>$data)
                                <tr>
                                    <td data-label="@lang('Date')">
                                        {{showDateTime($data->created_at)}}
                                    </td>
                                    <td  data-label="#@lang('Trx')">{{$data->trx}}</td>
                                    <td data-label="@lang('Details')">{{$data->details}}</td>
                                    <td data-label="@lang('Amount')">
                                        <strong @if($data->trx_type == '+') class="text-success" @else class="text-danger" @endif> {{($data->trx_type == '+') ? '+':'-'}} {{getAmount($data->amount)}} {{$general->cur_text}}</strong>
                                    </td>
                                    <td data-label="@lang('Remaining balance')">
                                        <strong>{{getAmount($data->post_balance)}} {{$general->cur_text}}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">{{__($empty_message)}}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>

                        {{$logs->links()}}
                    </div>

                </div>
            </div>
        </div>
    </section>


@endsection


@push('script')

@endpush

