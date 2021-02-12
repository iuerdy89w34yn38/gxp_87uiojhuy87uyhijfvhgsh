@php
    $latestDeposit = \App\Deposit::with('user', 'gateway')->where('status', 1)->latest()->limit(5)->get();
    $latestWithdraw = \App\Withdrawal::with('user', 'method')->where('status', 1)->latest()->limit(5)->get();

    $transactionContent = getContent('transaction.content',true);
@endphp
<section class="pt-120 pb-120">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center">
            <div class="section-header">
              <h2 class="section-title"><span class="font-weight-normal">{{ __(@$transactionContent->data_values->heading_w) }}</span> <b class="base--color">{{ __(@$transactionContent->data_values->heading_c) }}</b></h2>
              <p>{{ __(@$transactionContent->data_values->sub_heading) }}</p>
            </div>
          </div>
        </div><!-- row end -->
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <ul class="nav nav-tabs custom--style-two justify-content-center" id="transactionTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="deposit-tab" data-toggle="tab" href="#deposit" role="tab" aria-controls="deposit" aria-selected="true">@lang('Latest Deposit')</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="withdraw-tab" data-toggle="tab" href="#withdraw" role="tab" aria-controls="withdraw" aria-selected="false">@lang('Latest Withdraw')</a>
              </li>
            </ul>

            <div class="tab-content mt-4" id="transactionTabContent">
              <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">
                <div class="table-responsive--sm">
                  <table class="table style--two">
                    <thead>
                      <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Gateway')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($latestDeposit as $data)
                      <tr>
                        <td data-label="@lang('Name')">
                          <div class="user">
                            <div class="thumb"><img src="{{getImage('assets/images/user/profile/'. @$data->user->image)}}" alt="image"></div>
                            <span>{{__(@$data->user->fullname)}}</span>
                          </div>
                        </td>
                        <td data-label="@lang('Date')">{{showDateTime($data->created_at,'M d, Y')}}</td>
                        <td data-label="@lang('Amount')">{{__($general->cur_sym)}} {{getAmount($data->amount)}}</td>
                        <td data-label="@lang('Gateway')">{{__($data->gateway->name)}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="withdraw" role="tabpanel" aria-labelledby="withdraw-tab">
                <div class="table-responsive--md">
                  <table class="table style--two">
                    <thead>
                      <tr>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Date')</th>
                        <th scope="col">@lang('Amount')</th>
                        <th scope="col">@lang('Method')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($latestWithdraw as $data)
                      <tr>
                        <td data-label="@lang('Name')">
                          <div class="user">
                            <div class="thumb"><img src="{{getImage('assets/images/user/profile/'. @$data->user->image)}}" alt="image"></div>
                            <span>{{__(@$data->user->fullname)}}</span>
                          </div>
                        </td>
                        <td data-label="@lang('Date')">{{showDateTime($data->created_at,'M d, Y')}}</td>
                        <td data-label="@lang('Amount')">{{__($general->cur_sym)}} {{getAmount($data->amount)}}</td>
                        <td data-label="@lang('Method')">{{__(@$data->method->name)}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- tab-content end -->
          </div>
        </div>
      </div>
    </section>