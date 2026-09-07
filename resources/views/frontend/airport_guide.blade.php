@section("title",$page->meta_title)
@section("meta_keyword",$page->meta_keyword )
@section("meta_description",$page->meta_description)
@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.sb-serc a {
    width: 100%;
    margin-left: 0;
}
.sb-serc h4{
        font-size: 20x !important;
}
.sb-serc p{
    font-size: 13px !important;
    line-height: 20px !important;
}
.sb-serc a{
        margin-top: 20px !important;
}
.btn-submit{
    display: none;
}
.sb-serc{
    height: 330px !important;
}
</style>
    <div class="container">
    	<!-- end home-container -->
    	{!! $page->airport_parking !!} 
    </div>
    

@include('layouts.footer')