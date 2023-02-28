@php 
$total_fees = 0;
$total_due = 0;
$total_paid = 0;
$total_disc = 0;
$balance_fees = 0;
@endphp
<table id="" class="display school-table school-table-style-parent-fees" cellspacing="0" width="100%">
      <thead>
        <tr>
            <td class="text-right" colspan="14">
                <a class="primary-btn small fix-gr-bg modalLink text-right" data-modal-size="modal-lg" title="@lang('fees.add_fees')" href="{{route('student-direct-fees-total-payment', [$record->id])}}" >  <i class="ti-plus pr-2"> </i> @lang('fees.add_fees') </a>
            </td>
        </tr>
          <tr>
            <th class="nowrap">@lang('fees.installment') </th>
            <th class="nowrap">@lang('fees.amount') ({{@generalSetting()->currency_symbol}})</th>
            <th class="nowrap">@lang('common.status')</th>
            <th class="nowrap">@lang('fees.due_date') </th>
            <th class="nowrap">@lang('fees.payment_ID')</th>
            <th class="nowrap">@lang('fees.mode')</th>
            <th class="nowrap">@lang('fees.payment_date')</th>
            <th class="nowrap">@lang('fees.discount') ({{@generalSetting()->currency_symbol}})</th>
            <th class="nowrap">@lang('fees.paid') ({{@generalSetting()->currency_symbol}})</th>
            <th class="nowrap">@lang('fees.balance')</th>
            <th class="nowrap">@lang('common.action')</th>
          </tr>
      </thead>
      <tbody>
        @foreach($record->directFeesInstallments as $key=> $feesInstallment)
        @php 
        $total_fees += discountFees($feesInstallment->id); 
        $total_paid += $feesInstallment->paid_amount;
        $total_disc += $feesInstallment->discount_amount;
        $balance_fees += discountFees($feesInstallment->id) - ( $feesInstallment->paid_amount );
        @endphp 
            <tr>
                <td>{{@$feesInstallment->installment->title}}</td>
                <td> 
                    @if($feesInstallment->discount_amount > 0)
                      <del>  {{$feesInstallment->amount}}  </del>
                      {{$feesInstallment->amount - $feesInstallment->discount_amount}}
                    @else 
                     {{$feesInstallment->amount}}
                    @endif 
                  </td>
                  <td>
                      <button class="primary-btn small {{feesPaymentStatus($feesInstallment->id)[1]}} text-white border-0">{{feesPaymentStatus($feesInstallment->id)[0]}}</button> 
                  </td>
                <td>{{@dateConvert($feesInstallment->due_date)}}</td>
                <td>
                  
                </td>
                
                <td>
                    
                </td>
               
              <td>
                   
              </td>
              <td> {{$feesInstallment->discount_amount}}</td>
              <td>
                  {{$feesInstallment->paid_amount}}
              </td>
                 
              <td>
                  {{discountFees($feesInstallment->id) - ( $feesInstallment->paid_amount ) }} </td>

                  @if ( discountFees($feesInstallment->id) - ( $feesInstallment->paid_amount ) ==0 )
                  <td>
                    <div class="dropdown CRM_dropdown">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                            @lang('common.select')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item">@lang('fees.paid')</a>
                        </div>
                    </div>
                    </td>
                  @else
                  <td>
  
                    @php
                      $instalment_amount = $feesInstallment->amount;  
                    @endphp

                        <div class="dropdown CRM_dropdown">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                @lang('common.select') 
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                               
                                  @if(@$data['bank_info']->active_status == 1 || @$data['cheque_info']->active_status == 1 )
  
                                      @if( $feesInstallment->active_status != 1)
                                          <a class="dropdown-item modalLink" data-modal-size="modal-lg" title=" @lang('fees.add_payment') {{@$feesInstallment->installment->title}}"
                                              href="{{route('direct-fees-generate-modal-child', [directFees($feesInstallment->id),$feesInstallment->id,$feesInstallment->record_id ])}}"> 
                                              @lang('fees.add_payment')                                      
                                          </a>
                                      @endif
                                  @endif 
                       
                            </div>
                        </div>
                        
                </td>
                      
             
                    @endif 
            </tr>




            @php $this_installment = discountFees($feesInstallment->id); @endphp
            @foreach($feesInstallment->payments as $payment)
                @php $this_installment = $this_installment - $payment->paid_amount; @endphp
           
           <tr>
             <td></td>
             <td></td>
             <td></td>
             <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
             <td>
                 @if($payment->active_status == 1)
                 <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$payment->user->full_name}}">
                            {{@smFeesInvoice($payment->invoice_no)}}
                        </a>
                 @endif
             </td>
             <td>{{$payment->payment_mode}}</td>
             <td>{{@dateConvert($payment->payment_date)}}</td>
             <td>{{$payment->discount_amount}}</td>
             <td>{{$payment->paid_amount}}</td>
             <td>{{$this_installment}} </td>
             <td>
                <button class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
            </td>
          </tr>  
         @endforeach



            @endforeach



            <tfoot>
                <tr>
                    <th>@lang('fees.grand_total') ({{generalSetting()->currency_symbol}})</th>
                    <th>{{currency_format($total_fees)}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{currency_format($total_disc)}}</th>
                    <th>{{currency_format($total_paid)}} </th>
                    <th> {{$total_fees - ( $total_paid) }}</th>
                    <th>
                    </th>
                </tr>
            </tfoot>

      </tbody>
  </table>