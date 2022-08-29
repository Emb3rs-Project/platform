<div style="width:100%;display: flex; align-items: center; justify-items: center" class="full_page">
    <div>
        <img src="https://www.emb3rs.eu/wp-content/uploads/2019/12/EMB3Rs_horizontal-Logo.png" class="img">
        <h1 style="padding-left: 5px">Final Report</h1>
    </div>
</div>
<div class="full_page">

    <div id="toc_container">
        <p class="toc_title">Summary</p>
        <ul class="toc_list">
            @foreach($reports as $report)
            <li style="text-align: left">
                <a href="#{{\Illuminate\Support\Str::slug($report['module'])}}">{{$report['module']}} Report</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
<style>
    .full_page {
        height: 100vh;
    }

    #toc_container {
        background: #f9f9f9 none repeat scroll 0 0;
        border: 1px solid #aaa;
        display: table;
        font-size: 95%;
        margin-bottom: 1em;
        padding: 20px;
        width: 100%;
        margin-bottom: 15%;
    }

    .toc_title {
        font-weight: 700;
        text-align: center;
    }

    #toc_container li, #toc_container ul, #toc_container ul li{
        list-style: outside none none !important;
    }
</style>
@foreach($reports as $report)
<div id="{{\Illuminate\Support\Str::slug($report['module'])}}">&nbsp</div>
{!!$report['report'] !!}
<div style="margin-top: 15%">&nbsp;</div>
@endforeach
