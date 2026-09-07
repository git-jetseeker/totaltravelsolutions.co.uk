{{-- Accessible FAQ accordion item
     Usage: @include('partials.faq-item', ['id' => 'faq-1', 'question' => '...', 'answer' => '...'])
--}}
<div class="js-faq-item">
    <button type="button" class="js-faq-question" aria-expanded="false" aria-controls="{{ $id }}">
        <span class="js-faq-question__text">{!! $question !!}</span>
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="js-faq-answer" id="{{ $id }}">
        {!! $answer !!}
    </div>
</div>
