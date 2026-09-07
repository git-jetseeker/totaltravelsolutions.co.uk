<link property="stylesheet" rel='stylesheet' href='{{ asset('assets/page.css') }}' type='text/css' media='all' />

<style type="text/css">
    @media only screen and (min-width: 768px) {
        .margintop72 .passenger-detail {
            margin-left: 0px !important;
            width: 100%;
            padding: 10px
        }
    }

    .passenger-detail h3 {
        padding: 10px 20px;
        line-height: 1.6;
        background: linear-gradient(to right, #fa9e1b, orange);
        color: #fff;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
    }

    #faqshover a:hover {
        color: #C2185B;
    }

    .passenger-detail h1 {
        font-size: 25px;
        background: orange !important;
        padding: 0 20px;
        line-height: 1.6;
        background: linear-gradient(to right, #fa9e1b, orange);
        color: #fff;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .margintop72 .panel {
            margin-left: 0px;
        }
    }
</style>
@php
    $faq_titles = explode('#', $faqs['title']);
    $faq_descs = explode('#', $faqs['desc']);

@endphp






<section class="top-offer margintop72">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="panel passenger-detail">
                        <h1 class="light-weight" style="text-align:center">Frequently Asked Questions</h1>
                        <div class="well-body">

                            <div class="accordion" id="accordion">
                                <h3 class="light-weight" style="text-align:center">Parking</h3>

                                <div class="accordion-group">
                                    @php $i=1;  @endphp
                                    @foreach ($faq_titles as $faq_title)
                                        <div class="accordion-heading" id="faqshover">


                                            <a class="accordion-toggle collapsed" data-toggle="collapse"
                                                data-parent="#accordion2" href="#collapse{{ $i++ }}"
                                                aria-expanded="false">

                                                {!! $faq_title !!}
                                            </a>

                                        </div>

                                        @php $j=1;  @endphp
                                        @foreach ($faq_descs as $faq_desc)
                                            <div id="collapse{{ $j++ }}" class="accordion-body collapse"
                                                aria-expanded="false" style="height: auto;">
                                                <div class="accordion-inner">



                                                    {!! $faq_desc !!}


                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
